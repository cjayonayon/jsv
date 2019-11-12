<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Form\Admin\GroupType;
use App\Entity\Admin\Group;


/**
 * @Route("/admin/group")
 */
class GroupController extends AbstractController
{
    /**
     * @Route("/create", name="create_group")
     */
    public function index(Request $request)
    {
        $group = new Group();

        $form = $this->createForm(GroupType::class,$group);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form->getData()->getGroupBanner();
            if($uploadedFile){
                $destination = $this->getParameter('uploads_directory');
                    
                $newFilename = md5(uniqid()).'.'.$uploadedFile->guessExtension();
                $uploadedFile->move(
                    $destination,
                    $newFilename
                );
                $group->setGroupBanner($newFilename);
            }
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($group);
            $entityManager->flush();

            $this->addFlash('success', 'Group succesfully Created');
            return $this->redirectToRoute('create_group');
        }
        
        return $this->render('admin/group/group.html.twig',[
            'form' => $form->createView(),
            'img' => $group->getGroupBanner(),
        ]);
    }

       /**
     * @Route("/", name="view_group_info")
     */
    public function viewGroup()
    {
        $groups = $this->getDoctrine()
            ->getRepository(Group::class)
            ->findAll();
        
        return $this->render('admin/group/index.html.twig',[
            'groups' => $groups,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit_group_info")
     */
    public function editGroup(int $id, Request $request)
    {
        $group = $this->getDoctrine()
            ->getRepository(Group::class)
            ->find($id);
        
        if(!$group){
            throw $this->createNotFoundException("Page not Found");
        }
        $img =  $group->getGroupBanner();
        $group->setGroupBanner(null);   
        $form = $this->createForm(GroupType::class, $group);
        
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $group->getGroupBanner();

            if($uploadedFile){
                $destination = $this->getParameter('uploads_directory');
                    
                $newFilename = md5(uniqid()).'.'.$uploadedFile->guessExtension();
                $uploadedFile->move(
                    $destination,
                    $newFilename
                );
                $filePath = $destination.'/'.$img; //file path of the existing file
                // unlink($filePath); //deletes the file
                $group->setGroupBanner($newFilename);
            }else{
                $group->setGroupBanner($img);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($group);
            $entityManager->flush();

            return $this->redirectToRoute('view_group_info');
        }

        return $this->render('admin/group/group.html.twig',[
            'form' => $form->createView(),
            'img' => $img,
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete_group")
     */
    public function deleteInvitation(int $id){
        $group = $this->getDoctrine()
            ->getRepository(Group::class)
            ->find($id);

        if(!$group){
            throw $this->createNotFoundException("Page not Found");
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($group);
        $entityManager->flush();
        
        return $this->redirectToRoute('view_group_info');
    }
}

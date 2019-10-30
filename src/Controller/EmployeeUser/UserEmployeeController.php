<?php

namespace App\Controller\EmployeeUser;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\EmployeeUser\Items;
use App\Form\EmployeeUser\ItemType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Form\EmployeeUser\UploadItemType;
use App\Entity\EmployeeUser\EmployeeUser;
use App\Entity\EmployeeUser\Queue;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Sysadmin\User;

/**
 * @Route("/employee")
 */
class UserEmployeeController extends AbstractController
{   
    /**
     * @Route("/", name="user_employee")
     */
    public function index()
    {   
        $queueItem = $this->getDoctrine()
            ->getRepository(Queue::class)
            ->findQueueItems( $this->getUser()->getEmployeeId()->getId(), $this->getUser()->getEmployeeGroup()->getId() );

        $max = $this->getDoctrine()
            ->getRepository(Items::class)
            ->checkMaxItemPerHour($this->getUser()->getEmployeeId()->getId(), (new \dateTime())->modify('-1 hour'));
        $ctr = count($max);

        return $this->render('employee/index.html.twig',[
            'items' => $queueItem,
            'ctr' => $ctr
        ]);
    }

    /**
     * @Route("/update/queue", name="updateQueueItem")
     */
    public function updateQueueItem()
    {
        $queueItem = $this->getDoctrine()
            ->getRepository(Queue::class)
            ->findQueueItems( $this->getUser()->getEmployeeId()->getId(), $this->getUser()->getEmployeeGroup()->getId() );

        return new JsonResponse($queueItem);
    }

    /**
     * @Route("/update/start-seconds/{id}/{duration}", name="updateQueueStartSeconds")
     */
    public function updateQueueStartSeconds(int $id,int $duration)
    {
        $queueItem = $this->getDoctrine()
            ->getRepository(Queue::class)
            ->find($id);

        $queueItem->setStartSeconds($duration);
        $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($queueItem);
                    $entityManager->flush();

        return new JsonResponse($duration);        
    }

    /**
     * @Route("/add", name="add_items")
     */
    public function addItem(Request $request)
    {
        $employeeUser = $this->getDoctrine()
            ->getRepository(EmployeeUser::class)
            ->findOneBy(['username'=>$this->getUser()->getUsername()]);
        
        $adminUser = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy(['group'=>$employeeUser->getEmployeeGroup()]);

        $item = new Items();
        $item->setEmployee($employeeUser->getEmployeeId());
        $item->setItemGroup($employeeUser->getEmployeeGroup());
        $item->setAdminUser($adminUser);

        $form = $this->createForm(ItemType::class,$item);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $link = $item->getVideoId();
            $temp = explode('?v=', $link);
            $links = $temp[1];
            $temp2 = explode('&list=', $links);
            $videoId = $temp2[0];
            $item->setVideoId($videoId);

            if(sizeOf($temp2) > 1){
                $tempList = $temp2[1];
                $temp3 = explode('&', $tempList);
                $list = $temp3[0];
                $item->setPlaylist($list);
            }else{
                $item->setPlaylist('');
            }

            $employeeItem = $this->getDoctrine()
                ->getRepository(Items::class)
                ->getItemByEmployee($videoId, $employeeUser->getEmployeeGroup());

            if ($employeeItem){
                $employeeItem->setStatus('Add');
                $employeeItem->setRemovedAt(new \DateTime());

                $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($employeeItem);
                    $entityManager->flush();
                
                $this->addQueue($employeeItem, $videoId);                
                
            }else{   
                $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($item);
                    $entityManager->flush();
                
                $this->addQueue($item, $videoId);
            }
            return $this->redirectToRoute('user_employee');
        }

        return $this->render('employee/add.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/remove/button/{id}", name="remove_itemqueue")
     */
    public function removeItemQueue(int $id)
    {
        $queueItem = $this->getDoctrine()
            ->getRepository(Queue::class)
            ->find($id);

        if(!$queueItem){
            throw $this->createNotFoundException("Page not Found");
        }

        $item = $this->getDoctrine()
            ->getRepository(Items::class)
            ->find($queueItem->getItem()->getId());

        if(!$item){
            throw $this->createNotFoundException("Page not Found");
        }

        $queueItem = $this->getDoctrine()
            ->getRepository(Queue::class)
            ->findBy([ 'item'=>$item, 'employeeGroup'=>$this->getUser()->getEmployeeGroup()->getId() ]);

        foreach ($queueItem as $queue) {
            $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($queue);
                $entityManager->flush();
        }

        $checkItem = $this->getDoctrine()
            ->getRepository(Queue::class)
            ->findBy([ 'item'=>$item, 'employeeGroup'=>$this->getUser()->getEmployeeGroup()->getId() ]);

        if ($checkItem == null) {
            $item->setStatus('Removed');
            $item->setRemovedAt(new \DateTime());
            $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($item);
                $entityManager->flush();
        }

        return $this->redirectToRoute('user_employee');

    }

    /**
     * @Route("/remove/{id}", name="remove_items")
     */
    public function removeItem(int $id)
    {
        $queueItem = $this->getDoctrine()
            ->getRepository(Queue::class)
            ->find($id);
            
        if(!$queueItem){
            throw $this->createNotFoundException("Page not Found");
        }

        $item = $this->getDoctrine()
            ->getRepository(Items::class)
            ->find($queueItem->getItem()->getId());

        if(!$item){
            throw $this->createNotFoundException("Page not Found");
        }
        
        $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($queueItem);
            $entityManager->flush();

        $queue = $this->getDoctrine()
            ->getRepository(Queue::class)
            ->findBy(['employeeGroup'=>$this->getUser()->getEmployeeGroup(), 'item'=>$item]);

        if ($queue == null) {
            $item->setStatus('Removed');
            $item->setRemovedAt(new \DateTime());
            $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($item);
                $entityManager->flush();
        }

        return $this->redirectToRoute('user_employee');
    }

    /**
     * @Route("/upload", name="upload_items")
     */
    public function uploadItem(Request $request)
    {
        $employeeUser = $this->getDoctrine()
            ->getRepository(EmployeeUser::class)
            ->findOneBy(['username'=>$this->getUser()->getUsername()]);

        $uploadItem = new Items();
        
        $form = $this->createForm(UploadItemType::class,$uploadItem);
        $uploadItem->setVideoId('https://www.youtube.com/watch?v=null');
        $uploadItem->setPlaylist('');
        $uploadItem->setEmployee($employeeUser->getEmployeeId());
        $uploadItem->setItemGroup($employeeUser->getEmployeeGroup());

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['uploadFilename']->getData();
            if($uploadedFile){
                $destination = $this->getParameter('uploads_directory');
                    
                $newFilename = md5(uniqid()).'.'.$uploadedFile->guessExtension();
                $uploadedFile->move(
                    $destination,
                    $newFilename
                );
                $uploadItem->setUploadFilename($newFilename);
                $uploadItem->setVideoId($form['uploadFilename']->getData()->getClientOriginalName());
                
                $item = $this->getDoctrine()
                    ->getRepository(Items::class)
                    ->findOneBy([
                        'videoId'=>$form['uploadFilename']->getData()->getClientOriginalName(), 
                        'employee'=>$employeeUser->getEmployeeId()
                    ]);
                    
                if($item){
                    $item->setStatus('Add');
                    $item->setRemovedAt(new \DateTime());
                    
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($item);
                    $entityManager->flush();

                    $this->addQueue($item, $form['uploadFilename']->getData()->getClientOriginalName());

                }else{
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($uploadItem);
                    $entityManager->flush();

                    $this->addQueue($uploadItem, $form['uploadFilename']->getData()->getClientOriginalName());
                }
                    
                return $this->redirectToRoute('user_employee');
            }
        }
        
        return $this->render('employee/upload.html.twig',[
            'form' => $form->createView()
        ]);
    }

    private function addQueue(Items $item,string $filename){
        $employeeGroup = $this->getDoctrine()
            ->getRepository(EmployeeUser::class)
            ->findBy(['employeeGroup'=>$this->getUser()->getEmployeeGroup()]);

        foreach ($employeeGroup as $employee ) {
            $queueItem = new Queue();

            $queueItem->setEmployee($employee->getEmployeeId());
            $queueItem->setEmployeeGroup($employee->getEmployeeGroup());
            $queueItem->setVideoId($filename);
            $queueItem->setItem($item);
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($queueItem);
            $entityManager->flush();
        }
    }
}

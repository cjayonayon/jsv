<?php

namespace App\Controller\User;

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

/**
 * @Route("/user/stream")
 */
class StreamController extends AbstractController
{
    /**
     * @Route("/", name="user_stream")
     */
    public function index()
    {
        $queueItem = $this->getDoctrine()
            ->getRepository(Items::class)
            ->findAdminUserItems( $this->getUser(), $this->getUser()->getGroup()->getId() );
        // dd($queueItem);
        return $this->render('record_book/stream/stream.html.twig',[
            'items' => $queueItem,
        ]);
    }

    /**
     * @Route("/update/queue", name="updateAdminQueueItem")
     */
    public function updateQueueItem()
    {
        $queueItem = $this->getDoctrine()
            ->getRepository(Items::class)
            ->findAdminUserItems( $this->getUser(), $this->getUser()->getGroup()->getId() );

        return new JsonResponse($queueItem);
    }

    /**
     * @Route("/update/status/{id}", name="updateAdminStatus")
     */
    public function updateAdminStatus($id)
    {
        $queueItem = $this->getDoctrine()
            ->getRepository(Items::class)
            ->find($id);

        $queueItem->setAdminStatus('true');

        $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($queueItem);
                    $entityManager->flush();

        return new JsonResponse($queueItem);
    }

    /**
     * @Route("/add", name="user_add")
     */
    public function addStream(Request $request)
    {
        $item = new Items();
        $item->setItemGroup($this->getUser()->getGroup());
        $item->setAdminUser($this->getUser());

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
                ->getItemByEmployee($videoId, $this->getUser()->getGroup());

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
            return $this->redirectToRoute('user_stream');
        }
        

        return $this->render('record_book/stream/add.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/remove/item/{id}", name="admin_removeItem")
     */
    public function removeItem($id)
    {
        $item = $this->getDoctrine()
            ->getRepository(Items::class)
            ->find($id);
            
        if(!$item){
            throw $this->createNotFoundException("Page not Found");
        }

        $queue = $this->getDoctrine()
            ->getRepository(Queue::class)
            ->findBy(['employeeGroup'=>$this->getUser()->getGroup(), 'item'=>$item]);  
        
        foreach ($queue as $employeeQueue) {
            $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($employeeQueue);
                $entityManager->flush();
        }

        $queue = $this->getDoctrine()
            ->getRepository(Queue::class)
            ->findBy(['employeeGroup'=>$this->getUser()->getGroup(), 'item'=>$item]);

        if ($queue == null) {
            $item->setStatus('Removed');
            $item->setAdminQueue('Removed');
            $item->setRemovedAt(new \DateTime());
            $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($item);
                $entityManager->flush();
        }

        return $this->redirectToRoute('user_stream');
    }
    
    /**
     * @Route("/remove/queue/{id}", name="admin_removeQueue")
     */
    public function removeQueue($id)
    {
        $queueItem = $this->getDoctrine()
            ->getRepository(Items::class)
            ->find($id);

        if(!$queueItem){
            throw $this->createNotFoundException("Page not Found");
        }

        $queue = $this->getDoctrine()
            ->getRepository(Queue::class)
            ->findBy(['employeeGroup'=>$this->getUser()->getGroup(), 'item'=>$queueItem]);  

        if ($queue == null) {
            $queueItem->setStatus('Removed');
        }

        $queueItem->setAdminQueue('Removed');
            $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($queueItem);
                $entityManager->flush();
        
        return $this->redirectToRoute('user_stream');
    }

    /**
     * @Route("/upload/item", name="user_upload_item")
     */
    public function uploadItem(Request $request)
    {
        $uploadItem = new Items();
        
        $form = $this->createForm(UploadItemType::class,$uploadItem);
        $uploadItem->setVideoId('https://www.youtube.com/watch?v=null');
        $uploadItem->setPlaylist('');
        $uploadItem->setItemGroup($this->getUser()->getGroup());
        $uploadItem->setAdminUser($this->getUser());

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
                        'adminUser'=>$this->getUser()
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
                    
                return $this->redirectToRoute('user_stream');
            }
        }
        
        return $this->render('record_book/stream/upload.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/update/start-seconds/{id}/{duration}", name="updateAdminQueueStartSeconds")
     */
    public function updateQueueStartSeconds(int $id,int $duration)
    {
        $queueItem = $this->getDoctrine()
            ->getRepository(Items::class)
            ->find($id);

        $queueItem->setAdminStartSeconds($duration);
        $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($queueItem);
                    $entityManager->flush();

        return new JsonResponse($duration);        
    }

    private function addQueue(Items $item,string $filename){
        $employeeGroup = $this->getDoctrine()
            ->getRepository(EmployeeUser::class)
            ->findBy(['employeeGroup'=>$this->getUser()->getGroup()]);
            
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

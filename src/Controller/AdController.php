<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Repository\AdRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdController extends AbstractController
{
    /**
     * @Route("/ads", name="ads_index")
     */
    public function index(AdRepository $repo)
    {
        $ads = $repo->findAll();

        return $this->render('ad/index.html.twig', [
            'ads' => $ads
        ]);
    }

    /**
     *  Permet de créer une annonce
     * 
     * @Route("/ads/new", name="ads_create")
     * 
     * @return Response
     */
    public function create(Request $request, ObjectManager $manager){
        $ad = new Ad();

        $form = $this->createForm(AdType::class, $ad);

        $form->handleRequest($request);

        
        if($form->isSubmitted() && $form->isValid()) {
            foreach($ad->getImages() as $image) {
                $image->setAd($ad);
                $manager->persist($image);
            }

            $manager->persist($ad);
            $manager->flush();
            
            $this->addFlash(
                'success',
                "L'article {$ad->getTitle()} a bien été enregistrée."
            );

            return $this->redirectToRoute('ads_index', [
                'id' => $ad->getId()
            ]);
        }

        return $this->render('ad/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet d'afficher le formulaire d'édition
     *
     * @Route("/ads/{id}/edit", name="ads_edit")
     * 
     * @return Response
     */
    public function edit(Ad $ad, Request $request, ObjectManager $manager) {

        $form = $this->createForm(AdType::class, $ad);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            foreach($ad->getImages() as $image) {
                $image->setAd($ad);
                $manager->persist($image);
            }

            $manager->persist($ad);
            $manager->flush();
            
            $this->addFlash(
                'success',
                "Les modifications de l'article : {$ad->getTitle()} ont bien été enregistrées."
            );

            return $this->redirectToRoute('ads_show', [
                'id' => $ad->getId()
            ]);
        }

        return $this->render('ad/edit.html.twig', [
            'form' => $form->createView(),
            'ad' => $ad
        ]);
    }

    /**
     * Permet d'afficher une seul annonce
     *
     * @Route("/ads/{id}", name="ads_show")
     * 
     * @return Response
     */
        public function show(Ad $ad){
        return $this->render('ad/show.html.twig', [
            'ad' => $ad 
        ]);
        }

    /**
     * Permet de supprimer une annonce
     *
     * @Route("/ads/{id}/delete", name="ads_delete")
     * 
     * @return Response
     */
    public function delete(Ad $ad, ObjectManager $manager) {
        $manager->remove($ad);
        $manager->flush();

        $this->addFlash(
            'success',
            "L'article {$ad->getTitle()} a bien été supprimé"
        );

        return $this->redirectToRoute("ads_index");
    }

    /**
     * @Route("/admin/ads", name="admin_ads_index")
     */
    public function indexAdmin(AdRepository $repo)
    {
        $ads = $repo->findAll();

        return $this->render('admin/ad/index.html.twig', [
            'ads' => $ads
        ]);
    }

        /**
     *  Permet de créer une annonce
     * 
     * @Route("/admin/ads/new", name="admin_ads_create")
     * 
     * @return Response
     */
    public function adminCreate(Request $request, ObjectManager $manager){
        $ad = new Ad();

        $form = $this->createForm(AdType::class, $ad);

        $form->handleRequest($request);

        
        if($form->isSubmitted() && $form->isValid()) {
            foreach($ad->getImages() as $image) {
                $image->setAd($ad);
                $manager->persist($image);
            }

            $manager->persist($ad);
            $manager->flush();
            
            $this->addFlash(
                'success',
                "L'article {$ad->getTitle()} a bien été enregistré."
            );

            return $this->redirectToRoute('admin_ads_index');
        }

        return $this->render('admin/ad/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet d'afficher le formulaire d'édition
     *
     * @Route("/admin/ads/{id}/edit", name="admin_ads_edit")
     * 
     * @return Response
     */
    public function adminEdit(Ad $ad, Request $request, ObjectManager $manager) {

        $form = $this->createForm(AdType::class, $ad);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            foreach($ad->getImages() as $image) {
                $image->setAd($ad);
                $manager->persist($image);
            }

            $manager->persist($ad);
            $manager->flush();
            
            $this->addFlash(
                'success',
                "Les modifications de l'article '{$ad->getTitle()}' ont bien été enregistrées."
            );

            return $this->redirectToRoute('admin_ads_index', [
                'slug' => $ad->getId()
            ]);
        }

        return $this->render('admin/ad/edit.html.twig', [
            'form' => $form->createView(),
            'ad' => $ad
        ]);
    }

    /**
     * Permet de supprimer une annonce
     *
     * @Route("/admin/ads/{id}/delete", name="admin_ads_delete")
     * 
     * @return Response
     */
    public function adminDelete(Ad $ad, ObjectManager $manager) {
        $manager->remove($ad);
        $manager->flush();

        $this->addFlash(
            'success',
            "L'article '{$ad->getTitle()}' a bien été supprimé"
        );

        return $this->redirectToRoute("admin_ads_index");
    }
}

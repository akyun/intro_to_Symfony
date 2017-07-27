<?php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
*@Route("/inquiry")
*/
class InquiryController extends Controller
{
    /**
    * @Route("/")
    *Method("get")
    */
    public function indexAction ()
    {
        $form = $this->createFormBuilder()
            ->add('name', TextType::class)
            ->add('email', TextType::class)
            ->add('tel', TextType::class, [
                'required' => false,
            ])
            ->add('type', 'ChoiceType::class', [
                'choices' => [
                    '公演について',
                    'その他',
                ],
                'expanded' => true,
            ])
            ->add('content', 'textarea')
            ->add('submit', SubmitType::class, [
                'label' => '送信',
            ])
            ->getForm();

        return $this->render('Inquiry/index.html.twig',
            ['form' => $form->createView()]
        );
    }
}
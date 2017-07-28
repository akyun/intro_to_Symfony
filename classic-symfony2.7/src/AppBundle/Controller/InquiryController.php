<?php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Entity\Inquiry;

/**
*@Route("/inquiry")
*/
class InquiryController extends Controller
{
    /**
    * @Route("/")
    *Method("get")
    */
    public function indexAction()
    {
        return $this->render('Inquiry/index.html.twig',
            ['form' => $this->createInquiryForm()->createView()]
        );
    }

    /**
     * @Route("/")
     * @Method("post")
     */
    public function indexPostAction(Request $request)
    {
        $form = $this->createInquiryForm();
        $form->handleRequest($request);
        if ($form->isValid())
        {
            $inquiry = $form->getData();

            $inquiry = new Inquiry();
            $inquiry->setName($data['name']);
            $inquiry->setEmail($data['email']);
            $inquiry->setTel($data['tel']);
            $inquiry->setType($data['type']);
            $inquiry->setContent($data['content']);

            $em = $this->getDoctrine()->getManager();
            $em->persist($inquiry);
            $em->flush();

            $message = \Swift_Message::newInstance()
                ->setSubject('Webサイトからのお問い合わせ')
                ->setFrom('webmaster@exaple.com')
                ->setTo('admin@example.com')
                ->setBody(
                    $this->renderView(
                        'mail/inquiry.txt.twig',
                        ['data' => $inquiry]
                    )
                );

            $this->get('mailer')->send($message);

            return $this->redirect(
                $this->generateUrl('app_inquiry_complete'));
        }

        return $this->render('Inquiry/index.html.twig',
        ['form' => $form->createView()]
        );
    }




    /**
    * @Route("/complete")
    */
    public function completeAction()
    {
        return $this->render('Inquiry/complete.html.twig');
    }

    private function createInquiryForm ()
    {
        return $this->createFormBuilder(new Inquiry())
            ->add('name', TextType::class)
            ->add('email', TextType::class)
            ->add('tel', TextType::class, [
                'required' => false,
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    '公演について' => 0,
                    'その他' => 1
                ],
                'expanded' => true,
            ])
            ->add('content', TextareaType::class)
            ->add('submit', SubmitType::class, [
                'label' => '送信',
            ])
            ->getForm();

        return $this->render('Inquiry/index.html.twig',
            ['form' => $form->createView()]
        );
    }
}
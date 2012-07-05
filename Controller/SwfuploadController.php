<?php
namespace Fp\SwfuploadBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;

class SwfuploadController extends Controller
{
    /**
     * @param mixed     $data       The initial data for the form
     * @param array     $options    Options for the form
     *
     */
    public function getFormAction(array $options, $data = null)
    {
        $form = $this->createForm('swfupload_file', $data, $options);

        $this->renderView('FpSwfuploadBundle:Swfupload:getForm.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}

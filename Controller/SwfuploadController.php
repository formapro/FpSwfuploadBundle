<?php
namespace Fp\SwfuploadBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Extra;

class SwfuploadController extends Controller
{
    /**
     * @param mixed     $data       The initial data for the form
     * @param array     $options    Options for the form
     *
     * @Extra\Template(engine="twig")
     */
    public function getFormAction(array $options, $data = null)
    {
        $form = $this->createForm('swfupload_file', $data, $options);

        return array(
            'form' => $form->createView()
        );
    }
}

<?php
namespace Fp\SwfuploadBundle\Twig;

use Symfony\Component\Form\FormView;
use Symfony\Component\Templating\EngineInterface;

/**
 * @author Kotlyar Maksim <kotlyar.maksim@gmail.com>
 * @since 8/14/12
 */
class SwfuploadExtension extends \Twig_Extension
{
    /**
     * @var \Twig_Environment
     */
    protected $environment;

    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'form_swfupload' => new \Twig_Function_Method($this, 'formSwfuploadFunction')  
        );
    }

    /**
     * @param \Symfony\Component\Form\FormView $form
     * 
     * @throws \LogicException
     * 
     * @return string
     */
    public function formSwfuploadFunction(FormView $form)
    {
        $postParameters = array();
        $swfuploadForm = null;
        $this->convertForm($form, $postParameters, $swfuploadForm);

        if (false == $swfuploadForm) {
            throw new \LogicException('The form must have one swfupload form as a child. None found');
        }
        
        //post options with null values seems not working in swfupload. remove them.
        $postParameters = array_filter($postParameters);
            
        $swfuploadForm->vars['swfupload_post_parameters'] = $postParameters;
        
        $renderedForm = $this->environment->render('FpSwfuploadBundle:Swfupload:getForm.html.twig', array(
            'form' => $swfuploadForm
        ));
        
        return new \Twig_Markup($renderedForm, 'utf-8');
    }

    protected function convertForm(FormView $form, &$postParameters, &$swfuploadForm)
    {
        if (isset($form->vars['swfupload_upload_url'])) {
            if ($swfuploadForm) {
                throw new \LogicException('The form could have only one swfupload_file type as child');
            }

            $swfuploadForm = $form;

            return;
        }

        $postParameters[$form->vars['full_name']] = $form->vars['value'];
        foreach ($form as $childForm) {
            $this->convertForm($childForm, $postParameters, $swfuploadForm);
        }
    }
    
    public function getName()
    {
        return 'fp_swfupload';
    }
}

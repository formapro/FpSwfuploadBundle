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
            'form_swfupload_file' => new \Twig_Function_Method($this, 'formSwfuploadFileFunction'),
            'form_jquery_file' => new \Twig_Function_Method($this, 'formJqueryFileFunction')
        );
    }

    /**
     * @param \Symfony\Component\Form\FormView $form
     * 
     * @throws \LogicException
     * 
     * @return string
     */
    public function formSwfuploadFileFunction(FormView $form)
    {
        $postParameters = array();
        $swfuploadForm = null;
        $this->convertForm($form, $postParameters, $swfuploadForm, 'swfupload_upload_url');

        if (false == $swfuploadForm) {
            throw new \LogicException('The form must have one swfupload form as a child. None found');
        }
        
        //post options with null values seems not working in swfupload. remove them.
        $postParameters = array_filter($postParameters);
            
        $swfuploadForm->vars['swfupload_post_parameters'] = $postParameters;
        
        $renderedForm = $this->environment->render('FpSwfuploadBundle:Form:render.html.twig', array(
            'form' => $swfuploadForm
        ));
        
        return new \Twig_Markup($renderedForm, 'utf-8');
    }

    /**
     * @param \Symfony\Component\Form\FormView $form
     *
     * @throws \LogicException
     *
     * @return string
     */
    public function formJqueryFileFunction(FormView $form)
    {
        $postParameters = array();
        $jqueryFileForm = null;
        $this->convertForm($form, $postParameters, $jqueryFileForm, 'jquery_file_options');

        if (false == $jqueryFileForm instanceof FormView) {
            throw new \LogicException('The form must have one jquery file form as a child. None found');
        }

        //post options with null values seems not working in swfupload. remove them.
        $postParameters = array_filter($postParameters);

        $jqueryFileFormData = array();
        foreach ($postParameters as $name => $value) {
            $jqueryFileFormData[] = array('name' => $name, 'value' => $value);
        }

        $jqueryFileForm->vars['jquery_file_options']['multipart'] = true;
        $jqueryFileForm->vars['jquery_file_options']['formData'] = $jqueryFileFormData;

        $renderedForm = $this->environment->render('FpSwfuploadBundle:Form:render.html.twig', array(
            'form' => $jqueryFileForm
        ));

        return new \Twig_Markup($renderedForm, 'utf-8');
    }

    /**
     * @param FormView $form
     * @param array $postParameters
     * @param FormView|null $fileForm
     * @param string $fileFormDetectOption
     *
     * @throws \LogicException
     */
    protected function convertForm(FormView $form, &$postParameters, &$fileForm, $fileFormDetectOption)
    {
        if (isset($form->vars[$fileFormDetectOption])) {
            if ($fileForm) {
                throw new \LogicException('The form could have only one file form type as child');
            }

            $fileForm = $form;

            return;
        }

        $postParameters[$form->vars['full_name']] = $form->vars['value'];
        foreach ($form as $childForm) {
            $this->convertForm($childForm, $postParameters, $fileForm, $fileFormDetectOption);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'fp_swfupload';
    }
}

<?php
namespace Fp\SwfuploadBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormViewInterface;

class SwfuploadFileType extends AbstractType
{
    protected $debug;

    public function __construct($debug = false)
    {
        $this->debug = $debug;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /*
        foreach ($options as $name => $value) {
            if (0 === strpos($name, 'swfupload')) {
                $builder->setAttribute($name, $value);
            }
        }
        */
    }

    public function buildView(FormViewInterface $view, FormInterface $form, array $options)
    {
        $config = $form->getConfig();

        $options = $form->getConfig()->getOptions();
        foreach($options as $name => $value) {
            if(0 === strpos($name, 'swfupload')) {
                $view->setVar($name, $value);
            }
        }

        $view->setVar('swfupload_file_post_name',
            $config->getOption('swfupload_file_post_name')
                ? $config->getOption('swfupload_file_post_name')
                : $view->getVar('full_name')
        );
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults($this->getDefaultTypeOptions());
        $resolver->setRequired($this->getRequiredTypeOptionsNames());
    }

    /**
     * @return array
     */
    private function getRequiredTypeOptionsNames()
    {
        return array(
            'swfupload_upload_url',
            'swfupload_placeholder_container',
        );
    }

    /**
     *
     * @return array
     */
    private function getDefaultTypeOptions()
    {
        return array(
            'swfupload_file_post_name' => '',
            'swfupload_prevent_swf_caching' => false,
            'swfupload_debug' => $this->debug,
            'swfupload_file_types' => "*.*",
            'swfupload_file_types_description' => "Image Files",
            'swfupload_file_size_limit' => '3 MB',
            'swfupload_flash_url' => 'bundles/fpswfupload/flash/swfupload.swf',
            'swfupload_button_placeholder_selector' => '.swfupload-placeholder',
            'swfupload_button_image_url' => 'bundles/fpswfupload/images/invisible200x200.gif',
            'swfupload_button_width' => null,
            'swfupload_button_height' => null,
            'swfupload_button_text' => '',
            'swfupload_button_text_style' => '',
            'swfupload_button_cursor' => 'SWFUpload.CURSOR.HAND',
            'swfupload_flash_color' => '#000000',
            'swfupload_button_window_mode' => 'SWFUpload.WINDOW_MODE.TRANSPARENT',
            'swfupload_button_action' => 'SWFUpload.BUTTON_ACTION.SELECT_FILE',
            'swfupload_button_cursor' => 'SWFUpload.CURSOR.HAND',
        );
    }

    public function getParent()
    {
        return 'file';
    }

    public function getName()
    {
        return 'swfupload_file';
    }
}

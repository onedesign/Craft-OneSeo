<?php
namespace Craft;

/**
 * One SEO Plugin
 */
class OneSeoPlugin extends BasePlugin
{
    public function getName()
    {
        return Craft::t('SEO');
    }

    public function getVersion()
    {
        return '1.0.0';
    }

    public function getDeveloper()
    {
        return 'One Design Company';
    }

    public function getDeveloperUrl()
    {
        return 'http://www.onedesigncompany.com';
    }

    protected function defineSettings()
    {
        return array(
          'titleDividerCharacter' => array(
              AttributeType::String, 'label' => 'Meta Title Divider Character', 'default' => '–'
          )
        );
    }

    public function onAfterInstall()
    {
        craft()->oneSeo->run();
    }

    public function getSettingsHtml()
    {
        return craft()->templates->render('oneseo/settings', array(
           'settings' => $this->getSettings()
        ));
    }

    public function init()
    {
      craft()->templates->hook('getSeoMeta', function(&$context)
      {   
          if (isset($context['customMetaTitle']))
          {
            craft()->oneSeo_meta->setMetaTitle($context['customMetaTitle']);
          }

          if (isset($context['customMetaDescription']))
          {
            craft()->oneSeo_meta->setMetaDescription($context['customMetaDescription']);
          }
          
          if (isset($context['customMetaImage']))
          {
            craft()->oneSeo_meta->setMetaImage($context['customMetaImage']);
          }
      });
    }
}

<?php
namespace Craft;
class OneSeoVariable
{
    /**
     * Output combined meta information
     *
     * @return string
     */
    public function meta()
    {   
        $pluginSettings = craft()->plugins->getPlugin('oneSeo')->getSettings();

        $metaData = [
          'url' => craft()->getSiteUrl(),
          'pageTitle' => craft()->siteName
        ];

        $element = craft()->urlManager->getMatchedElement();

        if ($element && $element->getElementType() == ElementType::Entry)
        {
            // Look for a custom SEO title
            $pageTitle = $element->title;
            $customTitle = $element->seoTitle;
            if (strlen($customTitle)) $pageTitle = $customTitle;
            $pageTitle = $pageTitle . ' ' . $pluginSettings->titleDividerCharacter . ' ' . craft()->siteName;
            $metaData['pageTitle'] = $pageTitle;

            // Look for a custom SEO description
            $customDescription = $element->seoMetaDescription;
            if (strlen($customDescription)) $metaData['description'] = $customDescription;

            // Look for a custom SEO image
            $customImage = $element->seoImage;
            if ($customImage->count())
            {
                $metaData['image'] = $customImage[0]->url;
            }
            else
            {
                // Try to find existing images in the entry
                foreach ($element->getFieldLayout()->getFields() as $fieldLayoutField)
                {
                    $field = $fieldLayoutField->getField();
                    $type = $field->type;
                    if ($type == 'Assets')
                    {
                      $value = $element->getFieldValue($field->handle);
                      if ($value->count())
                      {
                        $metaData['image'] = $value[0]->url;
                        break;
                      }
                    }
                }
            }
            
            // Set URL
            $metaData['url'] = $element->url;
        }

        $originalPath = craft()->path->getTemplatesPath();
        $pluginTemplatePath = craft()->path->getPluginsPath() . 'oneseo/templates';
        craft()->path->setTemplatesPath($pluginTemplatePath);
        $html = craft()->templates->render('meta', $metaData);
        craft()->path->setTemplatesPath($originalPath);
        echo $html;
    }
}
<?php
namespace Craft;

/**
 * ODC SEO Service
 */
class OdcSeoService extends BaseApplicationComponent
{
  protected $seoGroupId;
  protected $seoFields = array();

  public function run() {
    $this->createSeoFields();
    $this->addFieldsToSections();
  }

  private function createSeoFields()
  {

    //
    //
    //  Create the Field Group
    //
    //////////////////////////////////////////////////////////

    OdcSeoPlugin::log('Creating the SEO field group.');

    $group = new FieldGroupModel();
    $group->name = 'SEO';

    if (craft()->fields->saveGroup($group))
    {
      OdcSeoPlugin::log('SEO group created successfully.' . $group->id);
      $this->seoGroupId = $group->id;
      OdcSeoPlugin::log('seoGroupId set to ' . $this->seoGroupId);
    }
    else
    {
      OdcSeoPlugin::log('Could not save the field group.', LogLevel::Warning);
    }

    //
    //
    //  Create the Title Field
    //
    //////////////////////////////////////////////////////////

    OdcSeoPlugin::log('Creating the SEO title field');
    $seoTitleField = craft()->fields->getFieldByHandle('seoTitle');
    if (!$seoTitleField) {
      $seoTitleField = new FieldModel();  
      $seoTitleField->groupId        = $group->id;
      $seoTitleField->name           = 'SEO Title';
      $seoTitleField->handle         = 'seoTitle';
      $seoTitleField->instructions   = 'Will be added to the <title> for the page. If not set, the Title for the entry will be used instead.';
      $seoTitleField->translatable   = true;
      $seoTitleField->type           = 'PlainText';
      $seoTitleField->settings       = array(
        'multiline' => '1',
        'placeholder' => '',
        'maxLength' => '',
        'initialRows' => ''
      );
    }

    if (craft()->fields->saveField($seoTitleField))
    {
      OdcSeoPlugin::log('SEO Title field created successfully.');
      $this->seoFields[] = $seoTitleField->id;
    }
    else
    {
      OdcSeoPlugin::log('Could not save the SEO Title Field.', LogLevel::Warning);
    }

    //
    //
    //  Create the Description Field
    //
    //////////////////////////////////////////////////////////

    OdcSeoPlugin::log('Creating the SEO meta description');

    $seoMetaDescriptionField = craft()->fields->getFieldByHandle('seoMetaDescription');
    if (!$seoMetaDescriptionField)
    {
      $seoMetaDescriptionField = new FieldModel();
      $seoMetaDescriptionField->groupId        = $group->id;
      $seoMetaDescriptionField->name           = 'SEO Meta Description';
      $seoMetaDescriptionField->handle         = 'seoMetaDescription';
      $seoMetaDescriptionField->instructions   = 'Will be used for the <meta name="description"> for the page';
      $seoMetaDescriptionField->translatable   = true;
      $seoMetaDescriptionField->type           = 'PlainText';
      $seoMetaDescriptionField->settings       = array(
        'multiline' => '1',
        'placeholder' => '',
        'maxLength' => '',
        'initialRows' => ''
      );
    }

    if (craft()->fields->saveField($seoMetaDescriptionField))
    {
      OdcSeoPlugin::log('SEO Meta field created successfully.');
      $this->seoFields[] = $seoMetaDescriptionField->id;
    }
    else
    {
      OdcSeoPlugin::log('Could not save the SEO meta description field.', LogLevel::Warning);
    }
    

    //
    //
    //  Create the Open Graph Image Field
    //
    //////////////////////////////////////////////////////////

    OdcSeoPlugin::log('Creating the SEO og:image field');

    $seoImageField = craft()->fields->getFieldByHandle('seoImage');
    if (!$seoImageField)
    {
      $seoImageField = new FieldModel();
      $seoImageField->groupId        = $group->id;
      $seoImageField->name           = 'SEO Image';
      $seoImageField->handle         = 'seoImage';
      $seoTitleField->instructions   = 'Will be used as the image representing the page when shared via social media.';
      $seoImageField->translatable   = false;
      $seoImageField->type           = 'Assets';
      $seoImageField->settings       = array(
        'useSingleFolder' => '',
        'defaultUploadLocationSubpath' => '',
        'singleUploadLocationSubpath' => '',
        'restrictFiles' => '1',
        'allowedKinds' => ['image'],
        'limit' => '1',
        'sectionLabel' => 'Add Image'
      );
    }

    // Save the Field and add it to our field array
    if (craft()->fields->saveField($seoImageField))
    {
      $this->seoFields[] = $seoImageField->id;
      OdcSeoPlugin::log('SEO og:image field created successfully.');
    }
    else
    {
      OdcSeoPlugin::log('Could not save the SEO og:image field.', LogLevel::Warning);
    }
    
  }

  public function addFieldsToSection($section) 
  {
    if ($section->hasUrls) 
    {
      $sectionEntryTypes = $section->getEntryTypes();

      foreach ($sectionEntryTypes as $entryType) 
      {
        $currentLayout = $entryType->getFieldLayout();
        $currentTabs = $currentLayout->getTabs();

        $postedFieldLayout = array();

        foreach ($currentTabs as $tab) {
          $fields = $tab->getFields();

          foreach ($fields as $field) {
            $postedFieldLayout[$tab->name][] = $field->fieldId;
          }
        }

        $postedFieldLayout['SEO'] = $this->seoFields;
        $requiredFields = array(); //Todo add support for required fields

        $fieldLayout = craft()->fields->assembleLayout($postedFieldLayout, $requiredFields);
        $fieldLayout->type = ElementType::Entry;
        $entryType->setFieldLayout($fieldLayout);

        // Save it
        if (craft()->sections->saveEntryType($entryType))
        {
          OdcSeoPlugin::log('Successfully appended fields.');
        }
        else 
        {
          OdcSeoPlugin::log('Could not append the fields.', LogLevel::Warning);
        }
      }
    }
  }

  public function addFieldsToSections() {
    $allSections = craft()->sections->getAllSections();
    foreach ($allSections as $section)
    {
      $this->addFieldsToSection($section);
    }
  }
}
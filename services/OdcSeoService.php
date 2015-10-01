<?php
namespace Craft;

/**
 * ODC SEO Service
 */
class OdcSeoService extends BaseApplicationComponent
{
  protected $seoGroupId;

  public function run() {
    $this->_createSeoFields();
  }

  private function _createSeoFields() {
    OdcSeoPlugin::log('Creating the SEO field group.');

    $group = new FieldGroupModel();
    $group->name = 'ODC SEO';

    if (craft()->fields->saveGroup($group))
    {
      OdcSeoPlugin::log('SEO group created successfully.' . $group->id);
      $this->seoGroupId = $group->id;
      OdcSeoPlugin::log('seoGroupId set to ' . $this->seoGroupId);
    } else {
      OdcSeoPlugin::log('Could not save the Default field group.', LogLevel::Warning);
    }

    OdcSeoPlugin::log('Creating the SEO Title Field');

    $seoTitleField = craft()->fields->getFieldByHandle('odcSeoTitle');
    if (!$seoTitleField) 
    {
      $seoTitleField = new FieldModel();
      $seoTitleField->groupId        = $group->id;
      $seoTitleField->name           = 'SEO Title';
      $seoTitleField->handle         = 'odcSeoTitle';
      $seoTitleField->translatable   = true;
      $seoTitleField->type           = 'PlainText';
      $seoTitleField->settings       = array(
        'multiline' => '1',
        'placeholder' => 'Enter SEO title ...',
        'maxLength' => '',
        'initialRows' => ''
      );

      if (craft()->fields->saveField($seoTitleField))
      {
        OdcSeoPlugin::log('SEO Title field created successfully.');
      }
      else
      {
        OdcSeoPlugin::log('Could not save the SEO Title Field.', LogLevel::Warning);
      }
    }
    
    OdcSeoPlugin::log('Creating the SEO meta Field');

    $seoMetaField = craft()->fields->getFieldByHandle('odcSeoMeta');
    if (!$seoMetaField)
    {
      $seoMetaField = new FieldModel();
      $seoMetaField->groupId        = $group->id;
      $seoMetaField->name           = 'SEO Meta';
      $seoMetaField->handle         = 'odcSeoMeta';
      $seoMetaField->translatable   = true;
      $seoMetaField->type           = 'PlainText';
      $seoMetaField->settings       = array(
        'multiline' => '1',
        'placeholder' => 'Enter meta description ...',
        'maxLength' => '155',
        'initialRows' => '8'
      );

      if (craft()->fields->saveField($seoMetaField))
      {
        OdcSeoPlugin::log('SEO Meta field created successfully.');
      }
      else
      {
        OdcSeoPlugin::log('Could not save the SEO Meta Field.', LogLevel::Warning);
      }
    }

    OdcSeoPlugin::log('Creating the SEO og:image Field');

    $seoImageField = craft()->fields->getFieldByHandle('odcSeoImage');
    if (!$seoImageField)
    {
      $seoImageField = new FieldModel();
      $seoImageField->groupId        = $group->id;
      $seoImageField->name           = 'SEO Share Image';
      $seoImageField->handle         = 'odcSeoImage';
      $seoImageField->translatable   = false;
      $seoImageField->type           = 'Assets';
      $seoImageField->settings       = array(
        'useSingleFolder' => '',
        'defaultUploadLocationSubpath' => '',
        'singleUploadLocationSubpath' => '',
        'restrictFiles' => '1',
        'allowedKinds' => ['image'],
        'limit' => '1',
        'sectionLabel' => 'Add Social Image'
      );

      if (craft()->fields->saveField($seoImageField))
      {
        OdcSeoPlugin::log('SEO og:image field created successfully.');
      }
      else
      {
        OdcSeoPlugin::log('Could not save the SEO og:image Field.', LogLevel::Warning);
      }
    }

    OdcSeoPlugin::log('Adding fields to sections with URLs');

    // Append SEO fields to that layout
    $seoFieldIds = array(
      $seoTitleField->id,
      $seoMetaField->id,
      $seoImageField->id
    );

    //TODO Allow user to select which sections get the field
    $allSections = craft()->sections->getAllSections();
    // We need to loop over every section
    foreach ($allSections as $section) {
      // if the hasUrls option is selected we care about it
      if ($section->hasUrls) {
        // We need to get all the entryTypes of this section
        $sectionEntryTypes = $section->getEntryTypes();
        // Then we loop over them 
        foreach ($sectionEntryTypes as $entryType) {
          $currentLayout = $entryType->getFieldLayout(); // returns FieldLayoutModel
          $currentTabs = $currentLayout->tabs; // returns [FieldLayoutTabModel]

          foreach ($currentTabs as $tab) { // Looping over 
            $fields = $tab->fields;
            foreach($fields as $field) {
              $currentFields[$tab->name][] = $field->field->id;
            }
          }

          //TODO Make field Group name a setting
          $currentFields[$group->name] = $seoFieldIds;
          Craft::dump($currentFields);
          $layout = craft()->fields->assembleLayout($currentFields, array(null)); // This will remove all required fields at the moment
          $layout->type = ElementType::Entry;
          $entryType->setFieldLayout($layout);
          Craft::dump($entryType->name);

          // if (craft()->sections->saveEntryType($entryType))
          // {
          //   OdcSeoPlugin::log($section->name . ' ' . $entryType . 'saved successfully');
          // }
          // else
          // {
          //   OdcSeoPlugin::log('Could not save the '. $section->name . ' '. $entryType . ' type.', LogLevel::Warning); 
          // }
        }
      }
    }

  }

  public function destroyFieldGroup() {
    //TODO Destroy the field groups on uninstall
  }
}
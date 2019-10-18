<?php


namespace Studio24\Frontend\Content\Translation;


use Studio24\Frontend\Content\Field\ArrayContent;
use Studio24\Frontend\Content\Field\Boolean;
use Studio24\Frontend\Content\Field\ContentFieldCollection;
use Studio24\Frontend\Content\Field\Date;
use Studio24\Frontend\Content\Field\DateTime;
use Studio24\Frontend\Content\Field\Decimal;
use Studio24\Frontend\Content\Field\Number;
use Studio24\Frontend\Content\Field\PlainArray;
use Studio24\Frontend\Content\Field\PlainText;
use Studio24\Frontend\Content\Field\RichText;
use Studio24\Frontend\Content\Field\ShortText;
use Studio24\Frontend\ContentModel\FieldInterface;

trait BasicContentFieldTranslationsTrait
{
    protected function resolveTextField(FieldInterface $contentModelField, $value) {
        return new ShortText($contentModelField->getName(), (string) $value);
    }

    protected function resolveNumberField(FieldInterface $contentModelField, $value) {
        return new Number($contentModelField->getName(), $value);
    }

    protected function resolveDecimalField(FieldInterface $contentModelField, $value) {
        $precision = $contentModelField->getOption('precision', $this->getContentModel());
        $round = $contentModelField->getOption('round', $this->getContentModel());
        return new Decimal($contentModelField->getName(), $value, $precision, $round);
    }

    protected function resolvePlaintextField(FieldInterface $contentModelField, $value) {
        return new PlainText($contentModelField->getName(), (string) $value);
    }

    protected function resolveRichtextField(FieldInterface $contentModelField, $value) {
        return new RichText($contentModelField->getName(), (string) $value);
    }

    protected function resolveDateField(FieldInterface $contentModelField, $value) {
        return new Date($contentModelField->getName(), $value);
    }

    protected function resolveDatetimeField(FieldInterface $contentModelField, $value) {
        return new DateTime($contentModelField->getName(), $value);
    }

    protected function resolveBooleanField(FieldInterface $contentModelField, $value) {
        return new Boolean($contentModelField->getName(), $value);
    }

    protected function resolveArrayField(FieldInterface $contentModelField, $value) {
        $array = new ArrayContent($contentModelField->getName());

        if (!is_array($value) || empty($value)) {
            return;
        }

        foreach ($value as $row) {

            // For each row add a set of content fields
            $item = new ContentFieldCollection();

            foreach ($contentModelField as $childField) {
                if (!isset($row[$childField->getName()])) {
                    continue;
                }

                $childValue = $row[$childField->getName()];
                $contentField = $this->resolveContentField($childField, $childValue);

                if ($contentField !== null) {
                    $item->addItem($this->resolveContentField($childField, $childValue));
                }
            }
            $array->addItem($item);
        }
        return $array;
    }

    protected function resolvePlainArrayField(FieldInterface $contentModelField, $value) {
        if (!is_array($value)) {
            return null;
        }
        return new PlainArray($contentModelField->getName(), $value);
    }
}
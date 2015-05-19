<?php
namespace Gettext\Languages\Exporter;

class Gettext extends Exporter
{
    private static function escapeString($str) {
        return '"'.addcslashes($str, "\0..\37").'"';
    }
    /**
     * @see Exporter::toStringDo
     */
    protected static function toStringDo($languages)
    {
        $result = 'struct plural_table_entry plural_table[] = {';
        $first = true;
        foreach ($languages as $language) {
            if($first) {
                $first = false;
            } else {
                $result .= ',';
            }
            $categories = array();
            $examples = array();
            foreach ($language->categories as $category) {
                $categories[] = self::escapeString($category->id);
                $examples[] = self::escapeString($category->examples);
            }
            $result .= "\n  {";
            $result .= "\n    ".self::escapeString($language->id);
            $result .= ",\n    ".self::escapeString($language->name);
            $result .= ",\n    ".self::escapeString('nplurals='.count($language->categories).'; plural='.$language->formula);
            $result .= ",\n    {".implode(', ', $categories).'}';
            $result .= ",\n    {".implode(', ', $examples).'}';
            $result .= "\n  }";
        }
        $result .= "\n};\n";

        return $result;
    }
    /**
     * @see Exporter::getDescription
     */
    public static function getDescription()
    {
        return 'Build an XML file - schema available at http://mlocati.github.io/cldr-to-gettext-plural-rules/GettextLanguages.xsd';
    }
}

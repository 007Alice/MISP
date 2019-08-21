<?php
include_once 'Polynomial.php';

class PolynomialExtended extends Polynomial
{
    public const DESCRIPTION = 'The implementation of the decaying formula from the paper `An indicator scoring method for MISP platforms` with support of the `Retention` taxonomy which overrides the final score.';

    function __construct() {
        // setup `retention` taxonomy
        $this->Taxonomy = ClassRegistry::init('Taxonomy');
        $retention_taxonomy_id = $this->Taxonomy->find('first', array(
            'recursive' => -1,
            'conditions' => array('LOWER(Taxonomy.namespace)' => 'retention'),
            'fields' => array('id')
        ));
        if (empty($retention_taxonomy_id)) {
            throw new Exception(__('`Retention` taxonomy not available'));
        } else {
            $retention_taxonomy_id = $retention_taxonomy_id['Taxonomy']['id'];
        }
        $taxonomy = $this->Taxonomy->getTaxonomy($retention_taxonomy_id, array('full' => true));
        $this->retention_taxonomy = array();
        foreach ($taxonomy['entries'] as $k => $entry) {
            $this->retention_taxonomy[$entry['tag']] = $entry['numerical_value'];
        }
    }

    public function computeScore($model, $attribute, $base_score, $elapsed_time)
    {
        if ($elapsed_time < 0) {
            return 0;
        }
        $decay_speed = $model['DecayingModel']['parameters']['decay_speed'];
        $lifetime = $model['DecayingModel']['parameters']['lifetime']*24*60*60;
        $score = $base_score * (1 - pow($elapsed_time / $lifetime, 1 / $decay_speed));

        // handle `retention` taxonomy tags 
        $temp = $this->__getPrioritizedTag($attribute);
        $tags = $temp['tags'];
        foreach ($tags as $tag) {
            $tagname = $tag['Tag']['name'];
            if (isset($this->retention_taxonomy[$tagname])) {
                $timestamp = intval($attribute['timestamp']);
                $now = time();
                $eol_time = $this->retention_taxonomy[$tagname] * 24 * 60 * 60; // `retention` taxonomy numerical_value are in seconds
                if (($now - $timestamp) > $eol_time) {
                    return 0;
                }
            }
        }
        return $score < 0 ? 0 : $score;
    }

    public function isDecayed($model, $attribute, $score)
    {
        $threshold = $model['DecayingModel']['parameters']['threshold'];
        return $threshold > $score;
    }
}
?>

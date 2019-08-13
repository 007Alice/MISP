<?php
include_once 'Base.php';

class PolynomialExtended extends DecayingModelBase
{

    public const EXTENDS_FORMULA = 'Polynomial';

    public function computeScore($model, $attribute, $base_score, $elapsed_time)
    {
        if ($elapsed_time < 0) {
            return 0;
        }
        $delta = $model['DecayingModel']['parameters']['delta'];
        $tau = $model['DecayingModel']['parameters']['tau']*24*60*60;
        $score = $base_score * (1 - pow($elapsed_time / $tau, 1 / $delta));
        return $score < 0 ? 0 : $score;
    }

    public function isDecayed($model, $attribute, $score)
    {
        $threshold = $model['DecayingModel']['parameters']['threshold'];
        return $threshold > $score;
    }
}
?>

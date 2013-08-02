<?php

class HidsSha1ExportComponent extends Component {

	public $rules = array();

	public function explain() {
		// unshift add in reverse order
		array_unshift($this->rules, '# ');
		array_unshift($this->rules, '# Keep in mind SHA-1 still has a theoretical collision possibility');
		array_unshift($this->rules, '# These HIDS export contains SHA-1 checksums.');
	}

	public function export($items) {
		$itemsDone = array();

		foreach ($items as &$item) {

			# sha-1
			$ruleFormat = '%s';

			$attribute = &$item['Attribute'];

			switch ($attribute['type']) {
				case 'sha1':
					if (!in_array ($attribute['value1'], $itemsDone)) {
						$this->checksumRule($ruleFormat, $attribute);
						$itemsDone[] = $attribute['value1'];
					}
					break;
				case 'filename|sha1':
					if (!in_array ($attribute['value2'], $itemsDone)) {
						$this->partRule($ruleFormat, $attribute);
						$itemsDone[] = $attribute['value2'];
					}
					break;
				default:
					break;

			}

		}

		sort($this->rules);
		$this->explain();

		return $this->rules;
	}

	public function checksumRule($ruleFormat, $attribute) {
		$this->rules[] = sprintf($ruleFormat,
				$attribute['value1']			// md5
				);
	}

	public function partRule($ruleFormat, $attribute) {
		$this->rules[] = sprintf($ruleFormat,
				$attribute['value2']			// md5
				);
	}

}

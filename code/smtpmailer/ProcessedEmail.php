<?php
/**
 * Same as the normal system email class, but runs the content through
 * Emogrifier to merge css style inline before sending.
 * @author Mark Guinn
 */
class ProcessedEmail extends Email {

	protected function parseVariables($isPlain = false) {
		parent::parseVariables($isPlain);

		// if it's an html email, filter it through emogrifier
		if (!$isPlain && preg_match('/<style[^>]*>(?:<\!--)?(.*)(?:-->)?<\/style>/ims', $this->body, $match)){
			$css = $match[1];
			$html = str_replace(
				array(
					"<p>\n<table>",
					"</table>\n</p>",
					'&copy ',
					$match[0],
				),
				array(
					"<table>",
					"</table>",
					'',
					'',
				), 
				$this->body
			);

			$emog = new Emogrifier($html, $css);
			$this->body = $emog->emogrify();
		}
	}
	
}
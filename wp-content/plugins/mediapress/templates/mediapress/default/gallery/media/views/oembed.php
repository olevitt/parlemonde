<?php
/**
 * Oembed Media View
 *
 * @package    MediaPress
 * @subpackage templates/default
 * @copyright  Copyright (c) 2018, Brajesh Singh
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     Brajesh Singh
 * @since      1.4.0
 */

$media = mpp_get_current_media();
if ( ! $media ) {
	return;
}
echo mpp_get_oembed_content( $media->id );

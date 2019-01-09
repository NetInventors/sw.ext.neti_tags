<?php
/**
 * @category NetiTags
 * @author   bmueller
 */

namespace NetiTags\Service\Tag\Relations;

/**
 * Interface RelationsTagsInterface
 *
 * @package NetiTags\Service\Tag\Relations
 */
interface RelationsTagsInterface extends RelationsInterface
{
    /**
     * @param int $tagId
     *
     * @return null|array
     */
    public function getRelationsForTag($tagId);
}

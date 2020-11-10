<?php

namespace SmCrunch;

use DateTimeImmutable;
use Exception;
use JsonSerializable;

/**
 * Describes a post in SM API.
 */
class Post implements JsonSerializable {

    public string $id;

    public EntityReference $from;

    public string $message;

    public string $type;

    public DateTimeImmutable $createdAt;

    /**
     * @param object $o
     *
     * @return Post
     * @throws Exception
     */
    public static function fromRaw( object $o ): Post {
        $p = new Post();
        $p->id = $o->id;
        $p->message = $o->message;
        $p->type = $o->type;
        $p->createdAt = new DateTimeImmutable( $o->created_time );

        $from = new EntityReference();
        $from->id = $o->from_id;
        $from->name = $o->from_name;
        $p->from = $from;

        return $p;
    }

    /**
     * Returns the Post object formatted per SM API.
     *
     * @return array
     */
    public function jsonSerialize() {
        return [
            'id' => $this->id,
            'from_id' => $this->from->id,
            'from_name' => $this->from->name,
            'message' => $this->message,
            'type' => $this->type,
            'created_time' => $this->createdAt->format( 'c' ),
        ];
    }

}

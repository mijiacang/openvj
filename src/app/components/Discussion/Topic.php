<?php

namespace VJ\Discussion;

use \VJ\I;

class Topic
{

    const RECORDS_PER_PAGE = 80;

    /**
     * 获取一个讨论主题的基本信息
     *
     * @param $topic_id
     *
     * @return array
     */
    public static function getInfo($topic_id)
    {

        if (is_array($topic_id) || $topic_id == null) {

            $record = $topic_id;

        } else {

            $mongo    = \Phalcon\DI::getDefault()->getShared('mongo');
            $topic_id = (string)$topic_id;

            $record = $mongo->Discussion->findOne(
                ['_id' => $topic_id],
                ['r' => 0]
            );

        }

        if ($record == null) {

            return [
                'count_all'     => 0,
                'count_comment' => 0,
                'pages'         => 0,
                'exist'         => false
            ];

        }

        $pages = ceil($record['countc'] / self::RECORDS_PER_PAGE);

        return [
            'count_all'     => $record['count'],
            'count_comment' => $record['countc'],
            'pages'         => $pages,
            'exist'         => true
        ];

    }

    /**
     * 获取讨论的评论内容和基本信息
     *
     * @param     $topic_id
     * @param int $page
     *
     * @return array
     */
    public static function get($topic_id, $page = 0)
    {

        $mongo    = \Phalcon\DI::getDefault()->getShared('mongo');
        $topic_id = (string)$topic_id;
        $page     = (int)$page;

        if ($page < 0) {
            return I::error('ARGUMENT_INVALID', 'page');
        }

        $record = $mongo->Discussion->findOne(
            ['_id' => $topic_id],
            [
                'r'      => ['$slice' => [$page * self::RECORDS_PER_PAGE, self::RECORDS_PER_PAGE]],
                'count'  => 1,
                'countc' => 1
            ]
        );

        $result = [
            'id'      => $topic_id,
            'info'    => self::getInfo($record),
            'comment' => []
        ];

        if ($record != null) {
            $result['comment'] = $record['r'];
        }

        return $result;

    }

}
<?php
return array(

    'blog' => array(
        'comment_social' => array(
            'duoshuo' => array(
                'websiteId' => '',
            ),
            'denglu' => array(
                'websiteId' => '',
            ),
            'disqus' => array(
                'websiteId' => '',
            ),
            'youyan' => array(
                'websiteId' => '',
            ),
            'livefyre' => array(
                'websiteId' => '',
            ),
        ),
    ),
    'view_helpers' => array(
        'invokables' => array(
            'duoshuoComment' => 'Blog\Helper\DuoshuoComment',
            'disqusComment' => 'Blog\Helper\DisqusComment',
            'youyanComment' => 'Blog\Helper\YouyanComment',
            'dengluComment' => 'Blog\Helper\DengluComment',
            'livefyreComment' => 'Blog\Helper\LivefyreComment',
        ),  
    ),
);

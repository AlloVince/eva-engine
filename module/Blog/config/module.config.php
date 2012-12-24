<?php
return array(

    'blog' => array(
        'comment_social' => array(
            'duoshuo' => array(
                'websiteId' => 'avnpc',
            ),
            'denglu' => array(
                'websiteId' => '25799denLkbKLwQl5KiGx3pKvsl4Y6',
            ),
            'disqus' => array(
                'websiteId' => 'avnpc',
            ),
            'youyan' => array(
                'websiteId' => '1500011',
            ),
            'livefyre' => array(
                'websiteId' => '302665',
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

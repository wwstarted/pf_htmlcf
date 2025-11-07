<?php
function get_provider_data_for_api($object)
{
    $provider_id = $object['id'];

    // Lấy dữ liệu serialize từ meta
    $provider_data = get_post_meta($provider_id, '_provider_data', true);

    // Unserialize nếu có dữ liệu
    if (!empty($provider_data) && is_string($provider_data)) {
        $provider_data = maybe_unserialize($provider_data);
    }

    // Trả về data đã unserialize hoặc object rỗng
    if (is_array($provider_data)) {
        // Lấy home info data
        $home_data = array(
            'tags' => isset($provider_data['tags']) ? $provider_data['tags'] : array(),
            'logo' => isset($provider_data['logo']) ? $provider_data['logo'] : '',
            'thumbnail' => isset($provider_data['thumbnail']) ? $provider_data['thumbnail'] : '',
            'summary' => isset($provider_data['summary']) ? $provider_data['summary'] : '',
            'rating' => isset($provider_data['rating']) ? floatval($provider_data['rating']) : 0,
            'advanced' => isset($provider_data['advanced']) ? $provider_data['advanced'] : array(),
            'price' => isset($provider_data['price']) ? floatval($provider_data['price']) : 0
        );

        // Lấy description data (tất cả sections)
        $description_data = array();
        if (isset($provider_data['description']) && is_array($provider_data['description'])) {
            $desc = $provider_data['description'];

            $description_data = array(
                'overview' => isset($desc['overview']) ? $desc['overview'] : '',
                'our_verdict' => isset($desc['our_verdict']) ? $desc['our_verdict'] : '',
                'best_for' => isset($desc['best_for']) ? $desc['best_for'] : array(),
                'not_ideal_for' => isset($desc['not_ideal_for']) ? $desc['not_ideal_for'] : array(),
                'detailed_ratings' => isset($desc['detailed_ratings']) ? $desc['detailed_ratings'] : array(),
                'pricing_plans' => isset($desc['pricing_plans']) ? $desc['pricing_plans'] : array(),
                'features_overview' => isset($desc['features_overview']) ? $desc['features_overview'] : array(),
                'perfect_for' => isset($desc['perfect_for']) ? $desc['perfect_for'] : array(),
                'security' => isset($desc['security']) ? $desc['security'] : array(
                    'encryption' => array(),
                    'compliance' => array(),
                    'authentication' => array(),
                    'privacy' => array()
                ),
                'support' => isset($desc['support']) ? $desc['support'] : array(
                    'availability' => array(),
                    'support_channels' => array(),
                    'languages' => array(),
                    'resources' => array()
                ),
                'user_reviews' => isset($desc['user_reviews']) ? $desc['user_reviews'] : array(),
                'faq' => isset($desc['faq']) ? $desc['faq'] : array(),
                'performance_metrics' => isset($desc['performance_metrics']) ? $desc['performance_metrics'] : array(),

            );
        }

        // Merge home data và description data
        return array_merge($home_data, array('description' => $description_data));
    }

    // Return default structure nếu không có data
    return array(
        'tags' => array(),
        'logo' => '',
        'thumbnail' => '',
        'summary' => '',
        'rating' => 0,
        'advanced' => array(),
        'price' => 0,
        'description' => array(
            'overview' => '',
            'our_verdict' => '',
            'best_for' => array(),
            'not_ideal_for' => array(),
            'detailed_ratings' => array(),
            'pricing_plans' => array(),
            'features_overview' => array(),
            'perfect_for' => array(),
            'security' => array(
                'encryption' => array(),
                'compliance' => array(),
                'authentication' => array(),
                'privacy' => array()
            ),
            'support' => array(
                'availability' => array(),
                'support_channels' => array(),
                'languages' => array(),
                'resources' => array()
            ),
            'user_reviews' => array(),
            'faq' => array(),
            'performance_metrics'=>array()
        )
    );
}

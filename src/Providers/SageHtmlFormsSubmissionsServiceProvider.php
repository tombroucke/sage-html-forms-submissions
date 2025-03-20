<?php

namespace Otomaties\SageHtmlFormsSubmissions\Providers;

use Illuminate\Support\ServiceProvider;

class SageHtmlFormsSubmissionsServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/html-forms-submissions.php',
            'html-forms-submissions'
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../config/html-forms-submissions.php' => $this->app->configPath('html-forms-submissions.php'),
        ], 'config');

        $this->loadViewsFrom(
            __DIR__.'/../../resources/views',
            'SageHtmlFormsSubmissions',
        );

        add_action('hf_output_form_settings', function ($form) {
            echo view('SageHtmlFormsSubmissions::form-settings', [
                'form' => $form,
            ]);
        });

        add_filter('hf_form_default_settings', function ($defaultSettings) {
            $defaultSettings['clear_cache'] = '';

            return $defaultSettings;
        });

        add_shortcode('sage_html_forms_submissions', function ($atts) {
            $attributes = shortcode_atts([
                'slug' => '',
                'id' => 0,
                'sort' => 'desc',
                'fields' => 'name:Name',
            ], $atts);

            $slug = sanitize_text_field($attributes['slug']);
            $id = absint($attributes['id']);
            $sort = in_array($attributes['sort'], ['asc', 'desc'], true) ? $attributes['sort'] : 'desc';
            $fields = sanitize_text_field($attributes['fields']);

            $fields = collect(explode(',', $fields));
            $fields = $fields->mapWithKeys(function ($item) {
                $item = explode(':', $item);

                return [$item[0] => $item[1]];
            });

            $form = hf_get_form($slug ? $slug : $id);

            return $this->submissionsTable($form, $fields, $sort);
        });

        add_shortcode('sage_html_forms_submissions_count', function ($atts) {
            $attributes = shortcode_atts([
                'slug' => '',
                'id' => 0,
            ], $atts);

            $form = hf_get_form($attributes['slug'] ? $attributes['slug'] : $attributes['id']);

            return count(hf_get_form_submissions($form->id));
        });

        add_action('hf_form_success', function (\HTML_Forms\submission $submission, \HTML_Forms\Form $form) {
            $pageIds = array_filter(explode(',', $form->settings['clear_cache']));

            if (! empty($pageIds)) {
                foreach ($pageIds as $pageId) {
                    if (function_exists('w3tc_flush_post')) {
                        w3tc_flush_post($pageId);
                    }
                    if (function_exists('wp_cache_post_change')) {
                        wp_cache_post_change($pageId);
                    }
                    if (function_exists('rocket_clean_post')) {
                        rocket_clean_post($pageId);
                    }
                    do_action('sage_html_forms_submission_clear_cache', $pageId);
                }
            }
        }, 10, 2);
    }

    private function submissionsTable(\HTML_Forms\Form $form, $fields, $sort = 'desc')
    {
        $submissions = array_values($this->submissionData(hf_get_form_submissions($form->id)));

        for ($i = 0; $i < count($submissions); $i++) {
            $submissions[$i]['index'] = $i + 1;
        }

        if ($sort == 'desc') {
            $submissions = array_reverse($submissions);
        }

        return view('SageHtmlFormsSubmissions::table', [
            'submissions' => $submissions,
            'fields' => $fields,
        ]);
    }

    private function submissionData(array $submissions)
    {
        return array_map(function ($submission) {
            $newSubmission = $submission->data;
            if (isset($submission->data['anonymous']) && $submission->data['anonymous']) {
                foreach ($newSubmission as $key => $value) {
                    $newSubmission[$key] = config('html-forms-submissions.strings.anonymous');
                }
            }

            return $newSubmission;
        }, $submissions);
    }
}

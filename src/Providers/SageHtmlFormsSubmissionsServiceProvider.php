<?php

namespace Otomaties\SageHtmlFormsSubmissions\Providers;

use Roots\Acorn\ServiceProvider;
use Illuminate\Support\Collection;

class SageHtmlFormsSubmissionsServiceProvider extends ServiceProvider
{
    /**
    * Register any application services.
    *
    * @return void
    */
    public function register()
    {
    }

    /**
    * Bootstrap any application services.
    *
    * @return void
    */
    public function boot()
    {
        $this->loadViewsFrom(
            __DIR__.'/../../resources/views',
            'SageHtmlFormsSubmissions',
        );

        add_action('hf_output_form_settings', function ($form) {
            echo view('SageHtmlFormsSubmissions::form-settings', [
                'form' => $form
            ]);
        });

        add_filter('hf_form_default_settings', function ($defaultSettings) {
            $defaultSettings['clear_cache'] = '';
            return $defaultSettings;
        });

        add_shortcode('sage_html_forms_submissions', function ($atts) {
            $attributes = shortcode_atts(array(
                'slug' => '',
                'id' => 0,
                'fields' => 'name:Name'
            ), $atts);

            $fields = collect(explode(',', $attributes['fields']));
            $fields = $fields->mapWithKeys(function ($item) {
                $item = explode(':', $item);
                return [$item[0] => $item[1]];
            });

            $form = hf_get_form($attributes['slug'] ? $attributes['slug'] : $attributes['id']);
            return $this->submissionsTable($form, $fields);
        });

        add_shortcode('sage_html_forms_submissions_count', function ($atts) {
            $attributes = shortcode_atts(array(
                'slug' => '',
                'id' => 0,
            ), $atts);

            $form = hf_get_form($attributes['slug'] ? $attributes['slug'] : $attributes['id']);
            return count(hf_get_form_submissions($form->id));
        });

        add_action('hf_form_success', function (\HTML_Forms\submission $submission, \HTML_Forms\Form $form) {
            $pageIds = array_filter(explode(',', $form->settings['clear_cache']));

            if (!empty($pageIds)) {
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

    private function submissionsTable(\HTML_Forms\Form $form, $fields)
    {
        $submissions = $this->submissionData(hf_get_form_submissions($form->id), $fields);
        return view('SageHtmlFormsSubmissions::table', [
            'submissions' => $submissions,
            'fields' => $fields
        ]);
    }

    private function submissionData(array $submissions, Collection $fields)
    {
        $data = [];
        foreach ($submissions as $submission) {
            $entry = [];
            foreach ($fields as $key => $label) {
                $entry[$key] = $submission->data[$key];
            }
            $data[] = $entry;
        }
        return $data;
    }
}

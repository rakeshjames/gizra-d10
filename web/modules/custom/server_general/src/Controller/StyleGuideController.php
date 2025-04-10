<?php

namespace Drupal\server_general\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Controller for the style guide page.
 */
class StyleGuideController extends ControllerBase {

  /**
   * Displays the style guide page.
   */
  public function styleGuide() {
    $build = [
      '#type' => 'container',
      '#attributes' => [
        'class' => ['bg-gray-100', 'min-h-screen', 'py-12', 'px-4', 'sm:px-6', 'lg:px-8'],
      ],
      'header' => [
        '#type' => 'html_tag',
        '#tag' => 'h1',
        '#value' => $this->t('Style Guide'),
        '#attributes' => [
          'class' => ['text-3xl', 'font-extrabold', 'text-center', 'text-gray-900', 'mb-12'],
        ],
      ],
      'content' => [
        '#type' => 'container',
        '#attributes' => [
          'class' => ['max-w-7xl', 'mx-auto'],
        ],
        'person_card_section' => [
          '#type' => 'container',
          '#attributes' => [
            'class' => ['mb-12', 'mt-8'],
          ],
          'heading' => [
            '#type' => 'html_tag',
            '#tag' => 'h2',
            '#value' => $this->t('Person Card'),
            '#attributes' => [
              'class' => ['text-2xl', 'font-bold', 'text-gray-800', 'mb-6'],
            ],
          ],
          'single_title' => [
            '#type' => 'html_tag',
            '#tag' => 'h3',
            '#value' => $this->t('Single Card Example'),
            '#attributes' => [
              'class' => ['text-xl', 'font-semibold', 'text-gray-900', 'mb-6'],
            ],
          ],
          'single' => [
            '#theme' => 'person_card',
            '#name' => 'Jane Cooper',
            '#role' => 'Paradigm Representative',
            '#description' => 'Admin',
            '#image' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=500&q=80',
          ],
          'grid_title' => [
            '#type' => 'html_tag',
            '#tag' => 'h3',
            '#value' => $this->t('Team Members Grid'),
            '#attributes' => [
              'class' => ['text-xl', 'font-semibold', 'text-gray-900', 'mt-16', 'mb-6'],
            ],
          ],
          'grid' => [
            '#type' => 'container',
            '#attributes' => [
              'class' => [
                'grid',
                'grid-cols-1',
                'md:grid-cols-2',
                'lg:grid-cols-3',
                'gap-6',
                'justify-items-center',
                'mx-auto',
                'max-w-7xl',
              ],
              'style' => 'padding: 24px;',
            ],
          ],
        ],
      ],
      '#attached' => [
        'library' => [
          'server_general/tailwind',
        ],
      ],
    ];

    for ($i = 0; $i < 10; $i++) {
      $build['content']['person_card_section']['grid'][$i] = [
        '#theme' => 'person_card',
        '#name' => 'Jane Cooper',
        '#role' => 'Paradigm Representative',
        '#description' => 'Admin',
        '#image' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=500&q=80',
      ];
    }

    return $build;
  }

  public function content(): array {
    $build = [
      '#theme' => 'server_style_guide',
      '#items' => [],
      '#cache' => [
        'tags' => [
          'config:system.theme.server_theme',
        ],
      ],
    ];

    $this->addCards($build);
    $this->addButtons($build);
    $this->addHeroImage($build);
    $this->addSearchInput($build);
    $this->addAccordion($build);
    $this->addCarousel($build);
    $this->addPersonCard($build);
    $this->addFooter($build);

    return $build;
  }

  /**
   * Add "Person Card" element.
   */
  protected function addPersonCard(array &$build) {
    $build['content']['person_card_section'] = [
      '#type' => 'details',
      '#title' => $this->t('Person Card'),
      '#open' => TRUE,
      '#weight' => 7,
    ];

    $build['content']['person_card_section']['single'] = [
      '#theme' => 'person_card',
      '#name' => 'Jane Cooper',
      '#role' => 'Paradigm Representative',
      '#description' => 'Admin',
      '#image' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=500&q=80',
    ];
  }
}

<?php

namespace Drupal\Tests\degov_common\Kernel;

use Drupal\degov_common\Common;
use Drupal\degov_common\Entity\NodeService;
use Drupal\KernelTests\KernelTestBase;
use Drupal\node\Entity\Node;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\paragraphs\Entity\ParagraphsType;
use Drupal\taxonomy\Entity\Term;
use Drupal\taxonomy\Entity\Vocabulary;

class CommonTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  public static $modules = [
    'user',
    'system',
    'node',
    'paragraphs',
    'degov_common',
    'config_replace',
    'video_embed_field',
		'paragraphs',
		'file',
    'text',
    'taxonomy'
  ];

  /**
   * @var \Drupal\degov_common\Entity\EntityService
   */
  private $entityService;


  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();
    $this->installEntitySchema('user');
    $this->installEntitySchema('paragraph');
    $this->installEntitySchema('taxonomy_term');
    $this->installSchema('system', ['sequences']);
    $this->installEntitySchema('node');
    $this->installSchema('node', 'node_access');
    \Drupal::moduleHandler()->loadInclude('paragraphs', 'install');
    \Drupal::moduleHandler()->loadInclude('taxonomy', 'install');
    $this->entityService = \Drupal::service('degov_common.entity');
  }

  public function testRemoveTaxonomyTerm() {

    $vocabulary = Vocabulary::create([
      'vid' => 'mytaxonomy',
      'description' => 'myTest',
      'name' => 'myTaxonomy'
    ])->save();
    $taxonomyTerm = Term::create([
      'name' => 'An Taxonomy term',
      'vid' => 'mytaxonomy'
    ])->save();

    $termLoaded = $this->entityService->load('taxonomy_term',[
      'vid' => 'mytaxonomy',
      'name' => 'An Taxonomy term'
    ]);
    self::assertTrue($termLoaded);
    Common::removeContent([
      'entity_type' => 'taxonomy_term',
      'entity_bundles' => ['mytaxonomy'],
    ]);
    $termID = $this->entityService->load('taxonomy_term', [
      'name' => 'An Taxonomy term',
      'vid' => 'mytaxonomy',
    ]);
    self::assertFalse($termID);
  }
  public function testRemoveNode() {
    $node = Node::create([
      'title' => 'An article node',
      'type' => 'article',
    ]);
    $node->save();

    $nodeID = $this->entityService->load('node', [
      'title' => 'An article node'
    ]);
    self::assertTrue($nodeID);
    Common::removeContent([
      'entity_type' => 'node',
      'entity_bundles' => ['article'],
    ]);
    $nodeID = $this->entityService->load('node', [
      'title' => 'An article node',
      'vid' => 'mytaxonomy',
    ]);
    self::assertFalse($nodeID);
  }

  public function testRemoveParagraph() {
		list($paragraph1, $paragraph2, $paragraph3) = $this->createParagraphs();

		$idParagraph1 = $paragraph1->id();
		$idParagraph2 = $paragraph2->id();
		$idParagraph3 = $paragraph3->id();

		$this->assertSame(\get_class(Paragraph::load($idParagraph1)), Paragraph::class);
		$this->assertSame(\get_class(Paragraph::load($idParagraph2)), Paragraph::class);
		$this->assertSame(\get_class(Paragraph::load($idParagraph3)), Paragraph::class);

		$node = Node::create([
			'title' => $this->randomMachineName(),
			'type' => 'article',
			'node_paragraph_field' => array($paragraph1, $paragraph2),
		]);
		$node->save();

		Common::removeContent([
			'entity_type' => 'paragraph',
			'entity_bundles' => ['test_text'],
		]);

		$this->assertSame(Paragraph::load($idParagraph1), NULL);
		$this->assertSame(Paragraph::load($idParagraph2), NULL);
		$this->assertSame(get_class(Paragraph::load($idParagraph3)), Paragraph::class);
	}

	private function createParagraphs(): array
	{
		$paragraph_type = ParagraphsType::create([
			'label' => 'test_text',
			'id'    => 'test_text',
		]);
		$paragraph_type->save();

		$paragraph_type = ParagraphsType::create([
			'label' => 'test_text_not_remove',
			'id'    => 'test_text_not_remove',
		]);
		$paragraph_type->save();

		$paragraph1 = Paragraph::create([
			'title' => 'Paragraph',
			'type'  => 'test_text',
		]);
		$paragraph1->save();

		$paragraph2 = Paragraph::create([
			'title' => 'Paragraph',
			'type'  => 'test_text',
		]);
		$paragraph2->save();

		$paragraph3 = Paragraph::create([
			'title' => 'Paragraph',
			'type'  => 'test_text_not_remove',
		]);
		$paragraph3->save();

		return [
			$paragraph1,
			$paragraph2,
			$paragraph3,
		];
	}

}

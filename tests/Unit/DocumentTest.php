<?php
namespace Tests\Unit;

use App\Http\Requests\StoreDocumentRequest;
use App\Models\Category;
use App\Models\Document;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Tests\TestCase;

class DocumentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Cria as categorias necessárias para os testes
        Category::create(['name' => 'Remessa']);
        Category::create(['name' => 'Remessa Parcial']);
    }

    /** @test */
    public function it_fails_when_content_exceeds_max_length()
    {
        // Dados de teste com o content excedendo o limite
        $data = [
            'title' => 'Sample Title',
            'content' => str_repeat('A', 256), // Excede o limite de 255 caracteres
            'category_id' => 1,
        ];

        // Instancia a StoreDocumentRequest e pega as rules
        $request = new StoreDocumentRequest();
        $rules = $request->rules();

        // Executa a validação
        $validator = Validator::make($data, $rules);

        // Verifica se a validação falha
        $this->assertTrue($validator->fails());
        $this->assertStringContainsString('not be greater than', $validator->errors()->first('content'));
    }


    /** @test */
    public function it_fails_if_title_does_not_contain_month_for_remessa_parcial_category()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('O título deve conter o nome de um mês para a categoria "Remessa Parcial".');

        $category = Category::where('name', 'Remessa Parcial')->first();

        Document::create([
            'title' => 'Documento sem mês',
            'content' => 'Conteúdo de teste',
            'category_id' => $category->id,
        ]);
    }

    /** @test */
    public function it_passes_if_title_contains_semestre_for_remessa_category()
    {
        $category = Category::where('name', 'Remessa')->first();

        $document = Document::create([
            'title' => 'Documento do primeiro semestre',
            'content' => 'Conteúdo de teste',
            'category_id' => $category->id,
        ]);

        $this->assertDatabaseHas('documents', ['id' => $document->id]);
    }

    /** @test */
    public function it_passes_if_title_contains_month_for_remessa_parcial_category()
    {
        $category = Category::where('name', 'Remessa Parcial')->first();

        $document = Document::create([
            'title' => 'Documento de Janeiro',
            'content' => 'Conteúdo de teste',
            'category_id' => $category->id,
        ]);

        $this->assertDatabaseHas('documents', ['id' => $document->id]);
    }
}

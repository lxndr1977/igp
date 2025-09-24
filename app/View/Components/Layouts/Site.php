<?php

namespace App\View\Components\Layouts;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Site extends Component
{
    // Tornamos as propriedades públicas para que a view do componente possa acessá-las
    public string $title;
    public string $description;

    /**
     * Crie uma nova instância do componente.
     *
     * @param string|null $title       // O '?' na frente de 'string' e o '= null' tornam o parâmetro opcional.
     * @param string|null $description // O mesmo aqui.
     */
    public function __construct(
        ?string $title = null,
        ?string $description = null
    ) {
        // Lógica para o título: se um título foi passado, anexe o nome do site. Senão, use só o nome do site.
        if ($title) {
            $this->title = $title . ' | ' . config('app.name');
        } else {
            $this->title = config('app.name');
        }

        // Lógica para a descrição: se uma descrição foi passada, use-a. Senão, use um padrão.
        $this->description = $description ?? 'Aqui vai a descrição padrão do seu incrível site.';
    }

    /**
     * Obtenha a view / conteúdo que representa o componente.
     */
    public function render(): View
    {
        // Certifique-se de que este caminho está correto para o seu arquivo de layout principal
        return view('components.layouts.site'); 
    }
}
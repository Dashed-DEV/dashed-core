<?php

namespace Qubiqx\QcommerceCore\Filament\Resources\TranslationResource\Pages;

use Closure;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Str;
use Qubiqx\QcommerceCore\Classes\Locales;
use Qubiqx\QcommerceCore\Filament\Resources\TranslationResource;
use Qubiqx\QcommerceCore\Models\Translation;

class ListTranslations extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = TranslationResource::class;
    protected static string $view = 'qcommerce-core::translations.pages.list-translations';

    public function mount(): void
    {
        $formData = [];
        $translations = Translation::all();
        foreach ($translations as $translation) {
            foreach (Locales::getLocales() as $locale) {
                $formData["translation_{$translation->id}_{$locale['id']}"] = $translation->getTranslation('value', $locale['id']);
            }
        }

        $this->form->fill($formData);
    }

    protected function getFormSchema(): array
    {
        $tags = Translation::distinct('tag')->orderBy('tag', 'ASC')->pluck('tag');
        $sections = [];

        foreach ($tags as $tag) {
            $translations = Translation::where('tag', $tag)->orderBy('name', 'ASC')->get();
            $tabs = [];

            foreach (Locales::getLocales() as $locale) {
                $schema = [];

                foreach ($translations as $translation) {
                    if ($translation->variables) {
                        $helperText = 'Beschikbare variablen: <br>';

                        foreach ($translation->variables as $key => $value) {
                            $helperText .= ":$key: (bijv: $value) <br>";
                        }
                    }

                    if ($translation->type == 'textarea') {
                        $schema[] = Textarea::make("translation_{$translation->id}_{$locale['id']}")
                            ->default($translation->default)
                            ->rows(5)
                            ->label(Str::of($translation->name)->replace('_', ' ')->replace('-', ' ')->title())
                            ->helperText($helperText ?? '')
                            ->reactive()
                            ->afterStateUpdated(function (Textarea $component, Closure $set, $state) {
                                $explode = explode('_', $component->getStatePath());
                                $translationId = $explode[1];
                                $locale = $explode[2];
                                $translation = Translation::find($translationId);
                                $translation->setTranslation("value", $locale, $state);
                                $translation->save();
                                $this->notify('success', Str::of($translation->name)->replace('_', ' ')->replace('-', ' ')->title() . " is opgeslagen");
                            });
                    } elseif ($translation->type == 'editor') {
                        $schema[] = RichEditor::make("translation_{$translation->id}_{$locale['id']}")
                            ->fileAttachmentsDisk('qcommerce-uploads')
                            ->toolbarButtons([
                                'attachFiles',
                                'blockquote',
                                'bold',
                                'bulletList',
                                'codeBlock',
                                'h2',
                                'h3',
                                'h4',
                                'italic',
                                'link',
                                'orderedList',
                                'redo',
                                'strike',
                                'undo',
                            ])
                            ->default($translation->default)
                            ->label(Str::of($translation->name)->replace('_', ' ')->replace('-', ' ')->title())
                            ->helperText($helperText ?? '')
                            ->reactive()
                            ->afterStateUpdated(function (RichEditor $component, Closure $set, $state) {
                                $explode = explode('_', $component->getStatePath());
                                $translationId = $explode[1];
                                $locale = $explode[2];
                                $translation = Translation::find($translationId);
                                $translation->setTranslation("value", $locale, $state);
                                $translation->save();
                                $this->notify('success', Str::of($translation->name)->replace('_', ' ')->replace('-', ' ')->title() . " is opgeslagen");
                            });
                    } elseif ($translation->type == 'image') {
                        $schema[] = FileUpload::make("translation_{$translation->id}_{$locale['id']}")
                            ->disk('qcommerce-uploads')
                            ->default($translation->default)
                            ->label(Str::of($translation->name)->replace('_', ' ')->replace('-', ' ')->title())
                            ->helperText($helperText ?? '')
                            ->reactive()
                            ->afterStateUpdated(function (FileUpload $component, Closure $set, $state) {
                                $explode = explode('_', $component->getStatePath());
                                $translationId = $explode[1];
                                $locale = $explode[2];
                                $translation = Translation::find($translationId);
                                $translation->setTranslation("value", $locale, $state);
                                $translation->save();
                                $this->notify('success', Str::of($translation->name)->replace('_', ' ')->replace('-', ' ')->title() . " is opgeslagen");
                            });
                    } else {
                        $schema[] = TextInput::make("translation_{$translation->id}_{$locale['id']}")
                            ->default($translation->default)
                            ->label(Str::of($translation->name)->replace('_', ' ')->replace('-', ' ')->title())
                            ->helperText($helperText ?? '')
                            ->default($translation->getTranslation('value', $locale['id']))
                            ->reactive()
                            ->afterStateUpdated(function (TextInput $component, Closure $set, $state) {
                                $explode = explode('_', $component->getStatePath());
                                $translationId = $explode[1];
                                $locale = $explode[2];
                                $translation = Translation::find($translationId);
                                $translation->setTranslation("value", $locale, $state);
                                $translation->save();
                                $this->notify('success', Str::of($translation->name)->replace('_', ' ')->replace('-', ' ')->title() . " is opgeslagen");
                            });
                    }
                }

                $tabs[] = Tab::make($locale['id'])
                    ->label(strtoupper($locale['id']))
                    ->schema($schema);
            }

            $sections[] = Section::make('Vertalingen voor ' . $tag)
                ->schema([
                    Tabs::make('Locales')
                        ->tabs($tabs),
                ])
                ->collapsible();
        }

        return $sections;
    }

//    public function submit()
//    {
//        $translations = Translation::all();
//        foreach ($translations as $translation) {
//            foreach (Locales::getLocales() as $locale) {
//                $translation->setTranslation("value", $locale['id'], $this->form->getState()["translation_{$translation->id}_{$locale['id']}"]);
//            }
//            $translation->save();
//        }
//
//        $this->notify('success', 'De vertalingen zijn opgeslagen');
//    }
}
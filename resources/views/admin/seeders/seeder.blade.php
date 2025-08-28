@extends('admin.layout.layout')
@section('header', $data['elements']['header'])
@section('sidebar', $data['elements']['sidebar'])
@section('css_js_header')
@endsection
@section('content')
    <main id="main-container">
        <div class="bg-body-light">
            <div class="content content-full">
                <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                    <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Seeder</h1>
                </div>
            </div>
        </div>

        <div class="content">
            @if(isset($data['items']))
                @foreach($data['items'] as $item)
                    <div class="block block-rounded">
                        @if($loop->first)
                            <div class="block-header"><h3 class="block-title">{{ $item['name'] }}</h3></div>
                        @else
                            <div class="block-header"><h3 class="block-title">{{ $item['name'] }}</h3></div>
                        @endif
                        <div class="block-content">
                            <div style="text-align:left;margin:0;width:100%;display:block;background:#272727;color:#fff;border-radius:10px;padding:10px;position:relative;">
                                <button type="button" class="btn btn-primary" onclick="copyText(this)" style="position:absolute;right:35px;top:10px;">Скопировать всё</button>
                                <div class="copy_block" style="display:block;width:100%;overflow:scroll;max-width:100%;max-height:400px;">
                                    @foreach($item['data'] as $line)
                                        <span style="white-space:nowrap;">{{ $line['line'] }}</span><br>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </main>

    <script>

        function copyText(button) {
            try {
                const contentBlock = button.closest('.block-content');
                const copyBlock = contentBlock.querySelector('.copy_block');
                const textToCopy = Array.from(copyBlock.querySelectorAll('span'))
                    .map(span => span.textContent).join('\n');

                // Пытаемся использовать API clipboard
                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(textToCopy)
                        .then(() => {
                            kbNotify('success', "Текст успешно скопирован в буфер обмена!");
                        })
                        .catch(err => {
                            console.error("Ошибка копирования текста через clipboard API:", err);
                            fallbackCopyTextToClipboard(textToCopy);
                        });
                } else {
                    // Используем запасной метод
                    fallbackCopyTextToClipboard(textToCopy);
                }
            } catch (err) {
                console.error("Ошибка выполнения функции copyText:", err);
            }
        }

        function fallbackCopyTextToClipboard(text) {
            try {
                // Создаем временное текстовое поле
                const textarea = document.createElement('textarea');
                textarea.value = text;

                // Добавляем его в документ
                document.body.appendChild(textarea);

                // Выделяем текст
                textarea.select();
                textarea.setSelectionRange(0, textarea.value.length); // Для мобильных устройств

                // Выполняем команду копирования
                const successful = document.execCommand('copy');
                if (successful) {
                    kbNotify('success', "Текст успешно скопирован в буфер обмена!");
                } else {
                    console.error("Не удалось скопировать текст через fallback метод.");
                }

                // Удаляем текстовое поле
                document.body.removeChild(textarea);
            } catch (err) {
                console.error("Ошибка копирования текста через fallback метод:", err);
            }
        }
    </script>

@endsection
@section('footer', $data['elements']['footer'])
@section('css_js_footer')
@endsection

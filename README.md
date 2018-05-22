# Парсер параметра pageclass_sfx в шаблонах Joomla plg_content_pageclass
Это один из вариантов решения проблемы стилизации разделов Joomla. В частности компонента материалов com_content.

При создании п. меню в параметрах имеется текстовое поле "CSS-класс страницы", с помощью которого можно добавить свои классы в шаблон материала, категории и т.д. И на основе этого родительского класса строить оформление заголовков, ссылок и т.д.

Но данный подход не всегда удобен!

Иногда требуется добавить/заменить классы отдельных блоков и приходится прописывать стили под каждый конкретный случай.

Итак, данный плагин анализирует содержимое поля "CSS-класс страницы" и на его основе создает дополнительные параметры:
1. headerclass_sfx
2. bodyclass_sfx
3. footerclass_sfx
4. pageclass_sfx

Пример данных: you_class page:page-medium-text header:header1 header2 _header3 body:body1 _body2 body-left footer:footer1 footer-top

Плагин выдаст следующие данные:
1. headerclass_sfx - "header1 header2 _header3"
2. bodyclass_sfx - "body1 _body2 body-left"
3. footerclass_sfx - "footer1 footer-top"
4. pageclass_sfx - "you_class page-medium-text"

Если в поле нет ни одного из спецтегов (body, header, footer, page), то в шаблон передается "you_class"

В шаблоне com_content нужно использовать следующий код:
$pageclass_sfx = $params->get('pageclass_sfx');
$headerclass_sfx = $params->get('headerclass_sfx');
$bodyclass_sfx = $params->get('bodyclass_sfx');
$footerclass_sfx = $params->get('footerclass_sfx');
и использовать данные переменные в нужных местах шаблона.

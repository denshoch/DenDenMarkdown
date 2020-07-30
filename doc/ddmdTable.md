DDmdTable（でんでんマークダウンTable拡張記法）
=========

Markdownで詳細な表を記述するための拡張。

- [概要](#abstract)
- [使い方](#usage)
- [記法](#syntax)
    - [基本](#basic)
    - [ヘッダーと見出しセル](#headers)
    - [行揃え](#alignment)
    - [セル結合](#span)
    - [キャプション](#caption)

<a name="abstract"></a>

概要
-----

DDmdTable（でんでんマークダウンTable拡張記法）はMarkdownで複雑な表（table）を記述するための独自拡張です。他のMarkdownライブラリとの互換性はありませんので注意してください。

DDmdTableは、[PHP Markdown ExtraのTable記法][]をベースにしていますが、部分的に[Textileの記法][]を取り入れた拡張をしています。各記法の違いについては次の表を参考にしてください。

| | PHP Markdown Extra | Textile | DDmdTable |
| :--- | :---: | :---: | :---: |
| ラッパー | No | No | Yes |
| 複数行ヘッダー | No | Yes | Yes |
| 見出しセル(`th`)指定 | No | Yes | Yes |
| `tfoot` | No | Yes | No |
| 行単位の行揃え | Yes | No | Yes |
| セル単位の行揃え | No | Yes | Yes |
| セルの中の縦揃え | No | Yes| No |
| セル結合 | No | Yes | Yes |
| キャプション | No | Yes | Yes |
| CSS記述 | No | Yes | No |
| `summary`属性 | No | Yes | No |
| `scope`属性 | No | No | Yes |

<a name="usage"></a>

使い方
------
DDmdTableは初期状態では有効になっていません。記法を有効にする設定を行います。

### でんでんコンバーター

[でんでんコンバーター][]では設定ファイル（`ddconv.yml`）の`options`で`ddmdTable`に`true`を指定します。

```
options:
  ddmdTable: true
```

### でんでんマークダウン

でんでんマークダウンを単体で使う場合には、インスタンス作成時にパラメタで指定します。

```php
$parser = new \Denshoch\DenDenMarkdown(array("ddmdTable" => true));
```

ラッパー
---------

DDmdTableではアクセシビリティ上の理由から `table` 要素を `div` 要素で囲んでいます。この`div` 要素をここではラッパーと呼びます。

ラッパーのクラス名の初期値は `tbl_wpr` です。

```
<div class="tbl_wpr">
<table>
〜
</table>
</div>
```

ラッパーのクラス名は `ddmdTableWrapperClass` パラメタで指定できます。

```
options:
  ddmdTable: true
  ddmdTableWrapperClass: hoge
```

```php
$parser = new \Denshoch\DenDenMarkdown(
  array(
    "ddmdTable" => true,
    "ddmdTableWrapperClass" => "hoge"
    )
  );
```

ラッパーで囲みたくない場合は、`ddmdTableWrapperClass` に空文字 `""` 指定してください。

なお、後続のコード例では簡略化のために、ラッパーを省略しています。

<a name="syntax"></a>

記法
----

<a name="basic"></a>

### 基本

PHP Markdown Extraの2種類のTable記法はそのまま使えます。

#### 行の両端をパイプ`|`で囲む記法

**DDmdTableではこちらを推奨します**。特にDDmdTable独自の機能を使う場合は、こちらの記法を基本としてください。

```
| Header 1 | Header 2 |
|-------- | -------- |
| Cell 1   | Cell 2 |
```

#### 行の両端のパイプ`|`を省略した記法

DDmdTableではこちらは非推奨です。Markdownと同程度の簡潔な表は作れますが、DDmdTableの独自機能は十分に利用できません。

```
Header 1 | Header 2
-------- | --------
Cell 1   | Cell 2
```

### セルの修飾子

Textileと同様に、各セルに対して、値の手前にピリオド`.`で終わる特定の文字列を記述することで、さまざまな指定が可能です。

```
|_>\2. Value |
```

DDmdTableでは次の指定が可能です。

- 見出しセル(`th`)
- 行揃え
- セル結合

<a name="headers"></a>

### ヘッダーと見出しセル

ハイフン`-`とパイプ`|`を組み合わせた区切り線より上はヘッダー(`thead`)、下はボディ(`tbody`)になります。ヘッダーの中のセルは全て見出しセル(`th`)になります。

またヘッダーの見出しセルは、`scope`属性によって列`col`（縦方向のセル）に対する見出しであることが示されます。

```
| Header 1 | Header 2 |
|-------- | -------- |
| Cell 1   | Cell 2 |
```

```html
<table>
<thead>
<tr>
<th scope="col">Header 1</th>
<th scope="col">Header 2</th>
</tr>
</thead>
<tbody>
<tr>
<td>Cell 1</td>
<td>Cell 2</td>
</tr>
</tbody>
</table>
```

<table>
<thead>
<tr>
<th scope="col">Header 1</th>
<th scope="col">Header 2</th>
</tr>
</thead>
<tbody>
<tr>
<td>Cell 1</td>
<td>Cell 2</td>
</tr>
</tbody>
</table>

多くのMarkdownライブラリではヘッダーは1行しか記述できませんが、DDmdTableではヘッダーを複数行記述することができます。

```
| Header 1 | Header 2 |
| Header 3 | Header 4 |
|-------- | -------- |
| Cell 1   | Cell 2 |
```

```html
<table>
<thead>
<tr>
<th scope="col">Header 1</th>
<th scope="col">Header 2</th>
</tr>
<tr>
<th scope="col">Header 3</th>
<th scope="col">Header 4</th>
</tr>
</thead>
<tbody>
<tr>
<td>Cell 1</td>
<td>Cell 2</td>
</tr>
</tbody>
</table>
```

<table>
<thead>
<tr>
<th scope="col">Header 1</th>
<th scope="col">Header 2</th>
</tr>
<tr>
<th scope="col">Header 3</th>
<th scope="col">Header 4</th>
</tr>
</thead>
<tbody>
<tr>
<td>Cell 1</td>
<td>Cell 2</td>
</tr>
</tbody>
</table>


アンダーバー`_`を使って、ボディに見出しセル（`th`）を作ることができます。ボディの見出しセルは、`scope`属性によって列`row`（横方向のセルに対する見出し）であることが示されます。


```
| Header 1 | Header 2 |
|-------- | -------- |
|_. Header 3 | Cell 1 |
|_. Header 4 | Cell 2 |
```

```html
<table>
<thead>
<tr>
<th scope="col">Header 1</th>
<th scope="col">Header 2</th>
</tr>
</thead>
<tbody>
<tr>
<th scope="row">Header 3</th>
<td>Cell 1</td>
</tr>
<tr>
<th scope="row">Header 4</th>
<td>Cell 2</td>
</tr>
</tbody>
</table>
```

<table>
<thead>
<tr>
<th scope="col">Header 1</th>
<th scope="col">Header 2</th>
</tr>
</thead>
<tbody>
<tr>
<th scope="row">Header 3</th>
<td>Cell 1</td>
</tr>
<tr>
<th scope="row">Header 4</th>
<td>Cell 2</td>
</tr>
</tbody>
</table>

ヘッダーの中でアンダーバー`_`による見出しセルの指定を行った場合、ヘッダーの見出しセルの`scope`属性が`row`（横方向のセルに対する見出し）に変化します。

```
|_. Header 1 | Header 2 |
|-------- | -------- |
|_. Header 3   | Cell 1 |
```

```html
<table>
<thead>
<tr>
<th scope="row">Header 1</th>
<th scope="col">Header 2</th>
</tr>
</thead>
<tbody>
<tr>
<th scope="row">Header 3</th>
<td>Cell 1</td>
</tr>
</tbody>
</table>
```

<table>
<thead>
<tr>
<th scope="row">Header 1</th>
<th scope="col">Header 2</th>
</tr>
</thead>
<tbody>
<tr>
<th scope="row">Header 3</th>
<td>Cell 1</td>
</tr>
</tbody>
</table>

<a name="alignment"></a>

### 行揃え

多くのMarkdownライブラリと同様に、区切り線の中にコロン`:`を使うことで、セルの中の行揃えを列ごとに指定できます。

```
| 左揃え | 中央揃え | 右揃え |
|:----- | :----: | ----: |
| 左揃え | 中央揃え | 右揃え |
```

```html
<table>
<thead>
<tr>
<th align="left" scope="col">左揃え</th>
<th align="center" scope="col">中央揃え</th>
<th align="right" scope="col">右揃え</th>
</tr>
</thead>
<tbody>
<tr>
<td align="left">左揃え</td>
<td align="center">中央揃え</td>
<td align="right">右揃え</td>
</tr>
</tbody>
</table>
```

<table>
<thead>
<tr>
<th align="left" scope="col">左揃え</th>
<th align="center" scope="col">中央揃え</th>
<th align="right" scope="col">右揃え</th>
</tr>
</thead>
<tbody>
<tr>
<td align="left">左揃え</td>
<td align="center">中央揃え</td>
<td align="right">右揃え</td>
</tr>
</tbody>
</table>

Textileのように、`<`、`=`、`>`の文字を使ってセルごとに行揃えを指定することもできます。

```
|<. 左揃え |=. 中央揃え |>. 右揃え |
|-------- |-------- |-------- |
|=. 中央揃え |>. 右揃え |<. 左揃え |
```

```html
<table>
<thead>
<tr>
<th align="left" scope="col">左揃え</th>
<th align="center" scope="col">中央揃え</th>
<th align="right" scope="col">右揃え</th>
</tr>
</thead>
<tbody>
<tr>
<td align="center">中央揃え</td>
<td align="right">右揃え</td>
<td align="left">左揃え</td>
</tr>
</tbody>
</table>
```

<table>
<thead>
<tr>
<th align="left" scope="col">左揃え</th>
<th align="center" scope="col">中央揃え</th>
<th align="right" scope="col">右揃え</th>
</tr>
</thead>
<tbody>
<tr>
<td align="center">中央揃え</td>
<td align="right">右揃え</td>
<td align="left">左揃え</td>
</tr>
</tbody>
</table>

列ごとの行揃えとセルごとの行揃えが競合した場合には、セルごとの行揃えが優先されます。

```
|>. 右揃え | 中央揃え | 右揃え |
|:----- | :----: | ----: |
| 左揃え | 中央揃え | 右揃え |
```

```html
<table>
<thead>
<tr>
<th align="right" scope="col">右揃え</th>
<th align="center" scope="col">中央揃え</th>
<th align="right" scope="col">右揃え</th>
</tr>
</thead>
<tbody>
<tr>
<td align="left">左揃え</td>
<td align="center">中央揃え</td>
<td align="right">右揃え</td>
</tr>
</tbody>
</table>
```

<table>
<thead>
<tr>
<th align="right" scope="col">右揃え</th>
<th align="center" scope="col">中央揃え</th>
<th align="right" scope="col">右揃え</th>
</tr>
</thead>
<tbody>
<tr>
<td align="left">左揃え</td>
<td align="center">中央揃え</td>
<td align="right">右揃え</td>
</tr>
</tbody>
</table>

<a name="span"></a>

### セル結合

セルの中でバックスラッシュ（半角円記号）`\`と数字を組み合わせて使うと、セルを数字の数だけ横方向に結合できます。

```
|\2. Header 1 | 
| Header 2 | Header 3
|-------- | --------
|\2. Cell 1 |
| Cell 2 | Cell 3
```

```html
<table>
<thead>
<tr>
<th scope="col" colspan="2">Header 1</th>
</tr>
<tr>
<th scope="col">Header 2</th>
<th scope="col">Header 3</th>
</tr>
</thead>
<tbody>
<tr>
<td colspan="2">Cell 1</td>
</tr>
<tr>
<td>Cell 2</td>
<td>Cell 3</td>
</tr>
</tbody>
</table>
```

<table>
<thead>
<tr>
<th scope="col" colspan="2">Header 1</th>
</tr>
<tr>
<th scope="col">Header 2</th>
<th scope="col">Header 3</th>
</tr>
</thead>
<tbody>
<tr>
<td colspan="2">Cell 1</td>
</tr>
<tr>
<td>Cell 2</td>
<td>Cell 3</td>
</tr>
</tbody>
</table>

セルの中でスラッシュ`/`と数字を組み合わせて使うと、セルを数字の数だけ縦方向に結合できます。

```
|/2. Header 1 | Header 2 |
| Header 3 |
|-------- | --------
|/2. Cell 1   | Cell 2 |
| Cell 3 |
```

```html
<table>
<thead>
<tr>
<th scope="col" rowspan="2">Header 1</th>
<th scope="col">Header 2</th>
</tr>
<tr>
<th scope="col">Header 3</th>
</tr>
</thead>
<tbody>
<tr>
<td rowspan="2">Cell 1</td>
<td>Cell 2</td>
</tr>
<tr>
<td>Cell 3</td>
</tr>
</tbody>
</table>
```

<table>
<thead>
<tr>
<th scope="col" rowspan="2">Header 1</th>
<th scope="col">Header 2</th>
</tr>
<tr>
<th scope="col">Header 3</th>
</tr>
</thead>
<tbody>
<tr>
<td rowspan="2">Cell 1</td>
<td>Cell 2</td>
</tr>
<tr>
<td>Cell 3</td>
</tr>
</tbody>
</table>

<a name="caption"></a>

### キャプション

表の先頭にある`|=.`から始まる行はキャプションになります。キャプションの行では行末のパイプ`|`は不要です（パイプがあると中央揃えのセルとして解釈されてしまいます）。

```
|=. This is caption
|Header 1 | Header 2
|-------- | --------
|Cell 1   | Cell 2
```

```html
<table>
<caption>This is caption</caption>
<thead>
<tr>
<th scope="col">Header 1</th>
<th scope="col">Header 2</th>
</tr>
</thead>
<tbody>
<tr>
<td>Cell 1</td>
<td>Cell 2</td>
</tr>
</tbody>
</table>
```

<table>
<caption>This is caption</caption>
<thead>
<tr>
<th scope="col">Header 1</th>
<th scope="col">Header 2</th>
</tr>
</thead>
<tbody>
<tr>
<td>Cell 1</td>
<td>Cell 2</td>
</tr>
</tbody>
</table>

以上

[PHP Markdown ExtraのTable記法]: https://michelf.ca/projects/php-markdown/extra/#table

[Textileの記法]: https://txstyle.org/doc/15/tables

[でんでんコンバーター]: https://conv.denshochan.com


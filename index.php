<?php
/**
 * 入力データを解析し、ビンゴデータと選択された単語の配列を返す
 *
 * @param string $input_data 入力データ
 * @return array 解析されたビンゴデータと選択された単語の配列
 */
function parse_input_data($input_data) {
    $lines = explode("\n", trim($input_data));
    $bingo_size = intval(array_shift($lines));
    $bingo_data = [];
    for ($i = 0; $i < $bingo_size; $i++) {
        $bingo_data[] = explode(" ", trim(array_shift($lines)));
    }
    $selected_count = intval(array_shift($lines));
    $selected_words = [];
    for ($i = 0; $i < $selected_count; $i++) {
        $selected_words[] = trim(array_shift($lines));
    }
    return ["bingo_data" => $bingo_data, "selected_words" => $selected_words];
}
/**
 * ビンゴカードのデータをHTML用に準備する
 *
 * @param array $bingo_data ビンゴカードの2次元配列
 * @param array $selected_words 選択された文字列の配列
 * @return array HTML用に変換されたビンゴカードの2次元配列
 */
function prepare_bingo_data($bingo_data, $selected_words) {

    $html_bingo_data = [];

    foreach ($bingo_data as $row) {
        $html_row = [];
        foreach ($row as $word) {
            if (in_array($word, $selected_words)) {
                $html_row[] = ["word" => $word, "selected" => true];
            } else {
                $html_row[] = ["word" => $word, "selected" => false];
            }
        }
        $html_bingo_data[] = $html_row;
    }
    return $html_bingo_data;
}
/**
 * ビンゴが達成されているかをチェックする
 *
 * @param array $html_bingo_data HTML用に変換されたビンゴカードの2次元配列
 * @return string ビンゴが達成されていれば "yes"、そうでなければ "no"
 */
function check_bingo($html_bingo_data) {
    
    $size = count($html_bingo_data);

    // 横方向のチェック
    for ($i = 0; $i < $size; $i++) {
        $bingo = true;
        for ($j = 0; $j < $size; $j++) {
            if (!$html_bingo_data[$i][$j]["selected"]) {
                $bingo = false;
                break;
            }
        }
        if ($bingo) {
            return "yes";
        }
    }

    // 縦方向のチェック
    for ($i = 0; $i < $size; $i++) {
        $bingo = true;
        for ($j = 0; $j < $size; $j++) {
            if (!$html_bingo_data[$j][$i]["selected"]) {
                $bingo = false;
                break;
            }
        }
        if ($bingo) {
            return "yes";
        }
    }

    // 斜め方向のチェック (左上から右下)
    $bingo = true;
    for ($i = 0; $i < $size; $i++) {
        if (!$html_bingo_data[$i][$i]["selected"]) {
            $bingo = false;
            break;
        }
    }
    if ($bingo) {
        return "yes";
    }

    // 斜め方向のチェック (右上から左下)
    $bingo = true;
    for ($i = 0; $i < $size; $i++) {
        if (!$html_bingo_data[$i][$size - 1 - $i]["selected"]) {
            $bingo = false;
            break;
        }
    }
    if ($bingo) {
        return "yes";
    }

    return "no";
}

$input_data = stream_get_contents(STDIN);

$lines = explode("\n", $input_data);
$bingo_size = (int)trim(array_shift($lines));
$bingo_data = [];

for ($i = 0; $i < $bingo_size; $i++) {
    $bingo_data[] = explode(" ", trim(array_shift($lines)));
}

$selected_size = (int)trim(array_shift($lines));
$selected_words = [];

for ($i = 0; $i < $selected_size; $i++) {
    $selected_words[] = trim(array_shift($lines));
}

// HTML用にデータを準備
$html_bingo_data = prepare_bingo_data($bingo_data, $selected_words);

// ビンゴ判定
$bingo_result = check_bingo($html_bingo_data);

// 結果を出力
echo $bingo_result . "\n";

// HTMLファイルの読み込み
include 'bingo.html';

?>
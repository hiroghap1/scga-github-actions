<?php
/*
Plugin Name: SC GitHub Actions
Description: GitHub Actions を実行するボタンを設置します
Version: 0.1.0
Author: HASEGAWA Yoshihiro in Stella Create Inc.
License: GPLv2
*/

// ファイルを管理画面にロード
function load_github_actions_script() {
    // メニューページ 'github-actions' でのみスクリプトをロード
    if (isset($_GET['page']) && $_GET['page'] === 'github-actions') {
        wp_enqueue_style('github-actions-style', plugin_dir_url(__FILE__) . 'css/github-actions-style.css');
        wp_enqueue_script('github-actions-script', plugin_dir_url(__FILE__) . 'js/github-actions-script.js', array(), null, true);
    }
}
add_action('admin_enqueue_scripts', 'load_github_actions_script');

// プラグインのメニューと設定ページを追加
function add_github_actions_menu() {
    add_menu_page(
        'GitHub Actions',
        'GitHub Actions',
        'manage_options',
        'github-actions',
        'github_actions_settings_page'
    );
}
// メニューを追加するアクションフック
add_action('admin_menu', 'add_github_actions_menu');

function github_actions_settings_page() {
    ?>
    <div class="wrap">
        <h1>GitHub Actions を実行します</h1>
        <p>設定を入力して実行ボタンをクリックすると、GitHub Actionsを実行します。</p>
        <!-- GitHub Actionsの実行ボタン -->
        <button id="run-github-actions" type="button" disabled>GitHub Actionsを実行</button>
        <hr>
        <h2>GitHub 設定</h2>
        <form method="post" action="options.php">
            <?php settings_fields('github-actions-settings-group'); ?>
            <?php do_settings_sections('github-actions-settings-group'); ?>
            
            <!-- GitHubユーザーネーム入力 -->
            <label for="github-username">GitHub Username:</label>
            <input type="text" id="github-username" name="github_username" value="<?php echo esc_attr(get_option('github_username')); ?>">

            <!-- リポジトリの入力 -->
            <label for="github-repo">GitHub Repository:</label>
            <input type="text" id="github-repo" name="github_repo" value="<?php echo esc_attr(get_option('github_repo')); ?>">

            <!-- トークンの入力 -->
            <label for="github-actions-token">GitHub Token(classic):</label>
            <input type="password" id="github-actions-token" name="github_actions_token" value="<?php echo esc_attr(get_option('github_actions_token')); ?>">

            <!-- workflowの実行ファイル名の入力 -->
            <label for="github-actions-file">Workflow file name:</label>
            <input type="text" id="github-actions-file" name="github_actions_file" value="<?php echo esc_attr(get_option('github_actions_file')); ?>">

            <?php submit_button('設定を保存'); ?>
        </form>
    </div>
    <?php
}

// GitHub Actions設定ページでの保存処理
function github_actions_save_settings() {
    if (isset($_POST['github_actions_token'])) {
        update_option('github_actions_token', sanitize_text_field($_POST['github_actions_token']));
    }
    if (isset($_POST['github_actions_file'])) {
        update_option('github_actions_file', sanitize_text_field($_POST['github_actions_file']));
    }
}

// GitHub Actions設定ページの保存アクションフック
add_action('admin_init', 'github_actions_save_settings');

// 設定の初期化
function github_actions_initialize_settings() {
    add_option('github_username', '');
    add_option('github_repo', '');
    add_option('github_actions_token', '');
    add_option('github_actions_file', ''); // 新しく追加したフィールド
}

// 設定の削除
function github_actions_delete_settings() {
    delete_option('github_username');
    delete_option('github_repo');
    delete_option('github_actions_token');
    delete_option('github_actions_file');
}

// プラグインが有効化されたときに初期化
register_activation_hook(__FILE__, 'github_actions_initialize_settings');

// プラグインが無効化されたときに設定を削除
register_deactivation_hook(__FILE__, 'github_actions_delete_settings');

// 設定のセクションとフィールドを追加
function github_actions_settings_init() {
    register_setting('github-actions-settings-group', 'github_username');
    register_setting('github-actions-settings-group', 'github_repo');
    register_setting('github-actions-settings-group', 'github_actions_token');
    register_setting('github-actions-settings-group', 'github_actions_file');
}

// 設定のセクションとフィールドを登録
add_action('admin_init', 'github_actions_settings_init');

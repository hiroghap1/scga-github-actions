document.addEventListener('DOMContentLoaded', function () {
    const button = document.getElementById('run-github-actions');
    const resultContainer = document.createElement('p');
    const githubUsername = document.getElementById('github-username').value;
    const githubRepo = document.getElementById('github-repo').value;
    const githubToken = document.getElementById('github-actions-token').value;
    const githubActionsFile = document.getElementById('github-actions-file').value;

    if (button) {
        if(githubUsername !== '' && githubRepo !== '' && githubToken !== '' && githubActionsFile !== '') {
            button.removeAttribute('disabled');
        }
        button.addEventListener('click', function () {
            button.setAttribute('disabled', '');
            // GitHub Actionsの実行をトリガーするためのHTTPリクエストを送信
            const xhr = new XMLHttpRequest();
            const apiUrl = `https://api.github.com/repos/${githubUsername}/${githubRepo}/actions/workflows/${githubActionsFile}/dispatches`;

            xhr.open('POST', apiUrl);
            xhr.setRequestHeader('Content-Type', 'application/json');
            xhr.setRequestHeader('Authorization', 'Bearer ' + githubToken);

            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    console.log('GitHub Actionsのレスポンス:', xhr.responseText);

                    if (xhr.status === 200) {
                        console.log('GitHub Actionsがトリガーされました:', JSON.parse(xhr.responseText));
                    } else {
                        console.error('GitHub Actionsのトリガーに失敗しました:', xhr.status, JSON.parse(xhr.responseText));
                    }
                }
            };

            const data = JSON.stringify({
                ref: 'main', // ブランチ名
            });

            xhr.send(data);

            button.insertAdjacentElement('afterend', resultContainer);
            resultContainer.innerHTML = `<a href="https://github.com/${githubUsername}/${githubRepo}/actions" target="_blank">GitHub Actionsのページ</a>を確認する`;
        });
    }
});
# KMLAOnline git repository #

큼온 git 저장소입니다.
git을 쓰면 더 쉽게 코드를 관리할 수 있어요.

## git이란? ##
git은 "버전 관리 시스템"(version control system)이에요.

이걸 쓰면 지금까지 코드가 어떻게 바뀌어 왔는지 추적하기 쉽고, 예전에 바꾼 내용을 되돌리기도 쉬워요.

특히 여러명이 같은 프로젝트를 관리할 때 편리해요.

git을 쓰면 더 이상 서버에 있는 파일을 직접 편집할 필요가 없어요.

오프라인으로 편집하고, 편집된 내용을 한번에 업로드할 수 있어요.

## git 사용방법 ##

대부분의 코드 에디터는 git 기능을 지원해요! 에디터 [brackets](http://brackets.io/)를 기준으로 설명할거지만 다른 에디터도 비슷할거에요.

1. 먼저, [여기](https://git-for-windows.github.io/)를 클릭해서 git을 설치하세요.

2. 그리고, brackets 오른쪽 바에 레고모양 버튼![lego.png](https://bitbucket.org/repo/e6RLGg/images/2353747815-lego.png)을 눌러서 "Brackets Git"라는 플러그인을 설치하세요.

3. 그런 후 오른쪽에 새로 생긴 버튼![git.png](https://bitbucket.org/repo/e6RLGg/images/2933550557-git.png)을 눌러요.

### 1. git clone ###

1. brackets 왼쪽 밑에 "clone" 버튼![clone.png](https://bitbucket.org/repo/e6RLGg/images/3285814024-clone.png)을 눌러요. (Project root is not empty라는 에러가 뜨면 파일->폴더 열기 로 비어있는 폴더를 열고 다시 해봐요.)

2. git url에 "https://kmlaonline@bitbucket.org/kmla/kmlaonline.git" 을 입력해요.

3. 유저네임/패스워드는 페북 그룹에 공지에 있어요. ("Save credentials"을 누르면 앞으로 비번 입력할 필요가 없어서 덜 귀찮을 거에요.)

4. OK를 누르면 git이 열심히 일을 할 거에요.

이건 한번만 하면 되요.

### 1.5 git pull ###

코드를 누가 바꿨다면, 바뀐 점을 다운받아야 해요. 혹시 모르니까 코딩하기 전에 pull하는게 좋아요.

1. 오른쪽 밑에 오른쪽으로 가리키는 화살표 모양 버튼![right.png](https://bitbucket.org/repo/e6RLGg/images/4187170110-right.png)을 클릭해요. 

2. OK를 눌러요.

### 2. git commit ###

git commit을 하면 파일에 바뀐 내용이 모두 저장되지만, 아직 업로드되진 않아요.

1. 편집하고 싶은 파일을 다 편집해요.

2. 다 했으면 밑에 있는 창에 있는 "commit" 버튼 왼쪽에 체크표시 하고 "commit"버튼![commit.png](https://bitbucket.org/repo/e6RLGg/images/2662529820-commit.png)을 눌러요.

3. 만약 처음이라면 이름과 이메일을 입력해야 될거에요. 이름을 입력하면 누가 무슨 파일을 바꿨는지 알 수 있어요.

4. 파일에 바뀐 내용이 표시될 거에요.

5. "Commit message"를 입력해요. 뭘 바꿨는지 쓰면 되요. 50자 이내로 적당히, 나중에 알아볼 수 있게 쓰세요!

6. "OK"를 눌러요.

### 3. git push ###

여기 있는 git 서버로 바뀐 내용을 업로드하는 과정이에요.

1. 오른쪽 밑에 위를 향하는 화살표 모양 버튼![up.png](https://bitbucket.org/repo/e6RLGg/images/3089217712-up.png)을 클릭해요.

2. OK를 눌러요.

### 4. git pull ###

바뀐 내용이 업로드되도 큼온 서버에 바로 나타나지 않아요. 바뀐 내용을 적용하려면 git pull을 해야 해요.

이걸 하기 위해서는 ssh 클라이언트가 필요해요. 윈도우 용으로는 [putty](http://www.chiark.greenend.org.uk/~sgtatham/putty/download.html)가 좋아요.

1. putty로 kmlaonline.net, 포트 220에 접속해요. 자신의 관리자 id/비번을 입력하면 되요.

2. 무섭게 생긴 검은 창에 흰 글씨가 있을 거에요. "cd /srv/http/kmla"라고 입력하고 엔터를 눌러요.

3. "git pull"이라고 입력하고 엔터를 눌러요.

4. 패스워드 입력하라고 하면 페북 그룹에 있는 패스워드를 입력해요.
# KMLA Online git repository #

큼온 git 저장소

## Linux 기초 Ref ##

https://www.linode.com/docs/tools-reference/linux-system-administration-basics

## git pull 방법 ##

```bash
cd /srv/http/kmla
git pull
```

## 서버 재부팅 후 할 것 정리 ##

```bash
cd /srv/http/gems
source ./init
./watch
```

참 쉽죠?

(이걸 안하면 .scss 파일이 컴파일 되지 않습니다)

## 서버 업데이트 방법 ##

```bash
sudo pacman -Syu
```

## ssl 인증서가 만기되었을 때 ##

(이론적으로 절대로 만기되지 않겠지만)

```bash
sudo certbot renew --email kmladotnet@gmail.com --agree-tos
```

## mysql, nginx(/etc/nginx/nginx.conf) 등의 설정을 바꾼 후 ##

서비스를 재시작해야지 설정이 적용됩니다

```bash
sudo systemctl restart mysqld
sudo systemctl restart nginx
```

## php 설정 (/etc/php/php.ini) 을 변경한 후 ##

php-fpm, nginx의 서비스를 재시작해야 설정이 적용됩니다.

```bash
sudo systemctl restart nginx
sudo systemctl restart php-fpm
```

## archlinux에서 새로운 패키지를 다운로드할 때 ##

pacman 명령어 사용 하기 - 아래 링크 참조
https://wiki.archlinux.org/index.php/Pacman

```bash
sudo pacman -S pkgname
```


## KMLA Online 멤버 관리 ##

자퇴생의 경우 로그인시 대기 알림이 뜨게 함. (/srv/http/kmla/board/user_pending_list 에서 'n_id.txt' 형태의 파일을 만들어 저장.)

## 급식 자동화 ##

급식 정보는 /scripts/food/data.json에서 가져옵니다. 이 정보는 cron에 의해 열흘에 한 번씩 갱신되며,
data.json 파일은 /scripts/food 디렉토리에 있는 python, java 파일들에 의해 생성됩니다.
만약 급식이 갱신되지 않아 웹에서 보이지 않는다면 /scripts/food/update.sh 를 실행.
```bash
/scripts/food/update.sh
```
cron 수정하기
```bash
crontab -e
```
자세한 정보는 /scripts/food/README.txt 참조!

## 기부물품 페이지 ##
기부물품 자동화 이후 기부물품과 관련된 데이터는 kmlaonline_donation_new 테이블에 저장됩니다.
페이지는 kmlaonline.net/util/donation-cloth, /util/donation-book 두 개로 나뉘어져 있습니다.
자동화 관련 파일들은 /scripts/donation/ 디렉토리에 있으며, 기부물품을 /scripts/donation/sample.xlsx에 나와있는 형식 그대로(엄격히!)
엑셀 파일에 입력해 /scripts/donation/ 디렉토리 안에 넣습니다. 그 뒤 /scripts/donation/insert.py 를 실행하면 데이터베이스에 자동으로 입력이 됩니다.


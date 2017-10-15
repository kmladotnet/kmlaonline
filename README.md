# KMLAOnline git repository #

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

### Odpalenie

Na windows trzeba pobrać stąd dockera https://www.docker.com/products/docker-desktop/ i go zainstalować ustawić o co prosi.
Następnie przejść w eksploratorze plików do folderu z aplikacją i w pasku lokalizacji wpisać cmd i kliknąć enter(musimy być w tym samym folderze co docker-compose.yml). 
W cmd wpisać docker compose up, by włączyć aplikacje bezpośrednio z logami wyświetlającymi się w cmd(polecam do pierwszego uruchomienia, bo wtedy wiadomo, kiedy skończyły budować się obrazy i instalować paczki do aplikacji),
można też użyć docker compose up -d, wtedy włączy się aplikacja w trybie detach oznacza to, że zamknięcie terminala nie spowoduje zamknięcia aplikacji lub wciśniecie ctrl+c.

Na linuxie
Polecam ubuntu lub debiana. Używałem wersji tylko cli.
W terminalu wpisujemy nastepujące komedy by zainstalować dockera.
for pkg in docker.io docker-doc docker-compose podman-docker containerd runc; do sudo apt-get remove $pkg; done
sudo apt-get update
sudo apt-get install ca-certificates curl gnupg
sudo install -m 0755 -d /etc/apt/keyrings
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /etc/apt/keyrings/docker.gpg
sudo chmod a+r /etc/apt/keyrings/docker.gpg
echo \
  "deb [arch="$(dpkg --print-architecture)" signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/ubuntu \
  "$(. /etc/os-release && echo "$VERSION_CODENAME")" stable" | \
  sudo tee /etc/apt/sources.list.d/docker.list > /dev/null
sudo apt-get update
sudo apt-get install docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin

i potem trzeba jeszcze by zainstalować gita
apt install git
i sklonować to repo to repo do dowolnego folderu i w tym folderze(folder z docker-compose.yml) podobnie jak w windowsie.
Wpisać docker compose up, by włączyć aplikacje bezpośrednio z logami wyświetlającymi się w cmd(polecam do pierwszego uruchomienia, bo wtedy wiadomo, kiedy skończyły budować się obrazy i instalować paczki do aplikacji),
można też użyć docker compose up -d, wtedy włączy się aplikacja w trybie detach oznacza to, że zamknięcie terminala nie spowoduje zamknięcia aplikacji lub wciśniecie ctrl+c.

by przejść do aplikacji na windowsie trzeba wpisać w przeglądarke localhost/login, a w linuxie trzeba się upewnić, czy port 80 jest udostepniony, jeśli tak to można w przeglądarke wpisać adres_ip_maszyny/login.

Następnie można się zarejestować i zalogować

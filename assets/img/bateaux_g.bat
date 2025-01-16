@echo off
cd /d C:\wamp64\www\bateuxlouer\assets\img\bateaux_g

rem Lista de novos nomes dos barcos de guerra franceses
set nomes=(
    "Le Charlemagne (1964)"
    "La Belle Poule (1786)"
    "Le Clemenceau (1961)"
    "Le De Grasse (1750)"
    "Le Richelieu (1940)"
    "Le Charles de Gaulle (2001)"
)

rem Contador para a lista de nomes
set i=0

rem Loop para renomear arquivos
for %%f in (*) do (
    set /a i+=1
    for %%n in (%nomes%) do (
        if !i! leq 6 ren "%%f" "%%~nf - %%~n%%~xf"
    )
)

echo Arquivos renomeados com sucesso.
pause

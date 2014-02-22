Clevis Skeleton
===============

Skeleton je vylepšený Nette sandbox pro modulární aplikace.

License: New BSD License
------------

Copyright (c) 2013 Clevis s.r.o. (http://clevis.cz) All rights reserved.

Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:

* Redistributions of source code must retain the above copyright notice,
this list of conditions and the following disclaimer.

* Redistributions in binary form must reproduce the above copyright notice,
this list of conditions and the following disclaimer in the documentation
and/or other materials provided with the distribution.

* Neither the name of "Clevis" nor the names of this software contributors
may be used to endorse or promote products derived from this software
without specific prior written permission.
This software is provided by the copyright holders and contributors "as is" and any express or implied warranties, including, but not limited to, the implied warranties of merchantability and fitness for a particular purpose are disclaimed. In no event shall the copyright owner or contributors be liable for any direct, indirect, incidental, special, exemplary, or consequential damages (including, but not limited to, procurement of substitute goods or services; loss of use, data, or profits; or business interruption) however caused and on any theory of liability, whether in contract, strict liability, or tort (including negligence or otherwise) arising in any way out of the use of this software, even if advised of the possibility of such damage.


Vlastnosti Skeletonu:
---------------------

Od Nette sandboxu se skeleton liší v následujících věcech:
- modularita - skleton umožňuje instalaci rozšiřitelných balíčků pomocí *Composeru*.
 	Balíčky mohou obsahovat presenter, šablony, assety, migrace, testy atd. (více v readme *SkeletonPackage*)
- podpora pro jednoduchou správu struktury databáze pomocí migrací
- zabudovaná podpora pro unit testy pomocí PhpUnit a webového rozhraní HttpPhpUnit
- zabudovaná podpora pro seleniové testy, včetně testovacích migrací a abstrakce nad funkčností pomocí knihovny Se34
- zabudovaná podpora pro model nad knihovnou Orm od PetrP (používá Dibi)
- pár drobných vylepšení...


Vytvoření nového projektu:
--------------------------

- Naklonujte tento repozitář
- Odeberte licence Skeletonu a Sandboxu
- Změňte globální namespace aplikace `App` v konfiguraci a v kódu
- Zkopírujte vytvořte lokální konfigurační soubor `app/config.local.neon`. Můžete použít vzor `config.local.examle.neon`
- Ujistěte se, že adresáře `temp` a `log` jsou zapisovatelné
- Pusťte `composer install --dev --prefer-dist`

Skeleton je ve výchozím stavu nastaven, aby verzoval i knihovny nainstalované *Composerem*. Je to bezpečnější volba,
usnadňuje vývoj kodérům a zjednodušuje deployment na podivné hostingy.


Součásti Skeletonu:
-------------------

- Skeleton21 - (tento repozitář) tvoří samotnou kostru, neobsahuje mnoho funkčnosti, protože ta by se špatně upravovala
- SkeletonCore - sem je vyčleněna základní funkčnost Skeletonu, aby bylo jednodušší aktualizova na již rozběhlém projektu
- SkeletonPackageInstaller - Composerový instalátor pro balíčky pro Skeleton
- SkeletonPackage - kostra pro nový balíček
- balíčky...

Další podpůrné knihovny:
- Migrations - jednoduchá knihovna pro migrace
- HttpPhpUnit - webová nadstavba pro spouštění testů
- Se34 - abstrakce nad stránkami (Features, PageObjects) pro testování stránek Seleniem


Todo:
-----

- installer, který se postará o vytvoření konfiguráku, změnu namespacu, založení databáze a inicializaci adresářů
- vyčlenit podporu pro Unit/Seleniové testy do vlastního repozitáře (zvážit podporu pro Nette\Tester)
- vyčlenit podporu pro Orm do vlastního repozitáře (zvážit podporu LeanMapperu)
- lepší podpora pro assety
- testy!

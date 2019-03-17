<?php


		/*
	#### PHP 1.5 yükleme####
	#### Programcı: Crpsem ©2017 ####
	#### GitHub: https://github.com/Crpsem/ ####
	*/

	/// Nerede Kuruluyum? ///
	$curdir = getcwd();
  $usbstick="0";
  
  if ( $_SERVER["argv"][1] == "usb" ) {
            // echo   $_SERVER["argv"][1];
            $usbstick="1";
            echo "\r\n  ########################################################################\n";
	echo "  #                                                                      #\r\n";
	echo "  #                            LAMPP Kurulumu                            #\r\n";;
	echo "  #                                                                      #\r\n";
	echo "  ########################################################################\r\n\r\n";
  }



	list($partition, $nonpartition) = preg_split ("/:/", $curdir); 
	$partwampp = substr(realpath(__FILE__), 0, strrpos(dirname(realpath(__FILE__)), '\\'));

	$directorwampp = NULL;                                                  
	if ($usbstick == "1" ) {
	   $dirpartwampp=$nonpartition;
  } else {
  	$dirpartwampp=$partwampp;
  }

	$awkpart = str_replace("&", "\\\\&", preg_replace ("/\\\\/i", "\\\\\\\\", $dirpartwampp));
	$awkpartdoublebackslash = str_replace("&", "\\\\&", preg_replace ("/\\\\/i", "\\\\\\\\\\\\\\\\", $dirpartwampp));
	$awkpartslash = str_replace("&", "\\\\&", preg_replace ("/\\\\/", "/", $dirpartwampp));

  	
	// Sadece hata ayıkla
  // echo $partition."\n";
	// 	echo $nonpartition."\n";
	//		echo $partwampp."\n\n";
          // echo $awkpart."\n"; 
				    // echo $awkpartslash."\n";
				          // exit;			
				
  $phpdir = $partwampp;
	$dir = preg_replace("/\\\\/", "/", $partwampp);
	$ppartition = "$partition:";

	/// Daha fazla lampp bilgisi icin install.sys + update.sys dosyasina ihtiyacim var
	$installsys = "install.sys";
	$installsysroot = $partwampp."\install\\".$installsys;

	/// Bazı update.sys dosyaları
	$perlupdatesys = "perlupdate.sys";
	$pythonupdatesys = "pythonupdate.sys";
	$serverupdatesys = "serverupdate.sys";
	$utilsupdatesys = "utilsupdate.sys";
	$javaupdatesys = "javaupdate.sys";
	$otherupdatesys = "otherupdate.sys";

	/// lampp ana dizini ...
	$substit = "\\\\\\\\lampp";
	$doublesubstit = "\\\\\\\\\\\\\\\\lampp";
	$substitslash = "/lampp";

	/// Küresel değişkenler
	$BS = 0;
	$CS = 0;
	$slashi = 1;
	$bslashi = 1;
	$awkexe = ".\install\awk.exe";
	$awk = ".\install\config.awk";
	$awknewdir = "\"".$awkpart."\"";
	$awkdoublebackslashdir = "\"".$awkpartdoublebackslash."\"";
	$awkslashdir = "\"".$awkpartslash."\"";
	if (file_exists("$partwampp\htdocs\\lampp\.version")) {
	$handle = fopen("$partwampp\htdocs\\lampp\.version","r");
  $lamppversion = fgets($handle);
  fclose($handle);
	} else {
		$lamppversion = "?.?.?";
    include_once "$partwampp\install\.version";
  }
  date_default_timezone_set('UTC');
	echo "\r\n  ########################################################################\n";
	echo "  # Apache lampp Win32 kurulum Surumu                                    #\r\n";
	echo "  #----------------------------------------------------------------------#\r\n";
	echo "  # Telif Hakki (c) 2017-".date("Y")." Apache $lamppversion                            #\r\n";
	echo "  #----------------------------------------------------------------------#\r\n";
	echo "  # Programci: Crpsem 2019                                               #\r\n";
	echo "  #                           GitHub: https://github.com/Crpsem/         #\r\n";
	echo "  ########################################################################\r\n\r\n";
	
	$confhttpdroot = $partwampp."\apache\\conf\\httpd.conf";

	// İnstall.sys dosyasında lampp basic paketi için yükleme durumunu bulur
	if (file_exists($installsysroot)) {
		$i = 0;
		$datei = fopen($installsysroot, 'r');
		while (!feof($datei)) {
			$zeile = fgets($datei, 255);
			if ( $zeile == "usbstick = 1" ) {
        echo "  USB stick installation found! Using relative paths by default ($nonpartition).";
          $dirpartwampp=$nonpartition;
          $usbstick="1";
          $partwampp=$nonpartition;
        //exit;
      } 
			$sysroot[] = $zeile;
			$i += 1;
		}
		fclose($datei);

		$sysroot[2] = str_replace('perl', 'server', $sysroot[2]); 
		file_put_contents($installsysroot, implode('', $sysroot));

		list($left, $right) = preg_split ("/ = /", $sysroot[0]);
		$right = preg_replace ("/\r\n/i", "", $right);
		if (strtolower($partwampp) == strtolower($right)) {
			$lamppinstaller = "nothingtodo";
		} else {
			$lamppinstaller = "newpath";
			$substit = preg_replace ("/\\\\/i", "\\\\\\\\\\\\\\\\", $right);
			$doublesubstit = preg_replace ("/\\\\/i", "\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\", $right);
			$substitslash = preg_replace("/\\\\/i", "/", $right);
		}
	} else {
		$installsys = fopen($installsysroot, 'w');
		if ( $usbstick == "1" ) {
		$wamppinfo = "DIR = $nonpartition\r\nlampp = $lamppversion\r\nserver = 0\r\nperl = 0\r\npython = 0\r\nutils = 0\r\njava = 0\r\nother = 0\r\nusbstick = $usbstick";
		} else {
    $wamppinfo = "DIR = $partwampp\r\nlampp = $lamppversion\r\nserver = 0\r\nperl = 0\r\npython = 0\r\nutils = 0\r\njava = 0\r\nother = 0\r\nusbstick = $usbstick";
    }
    fputs($installsys, $wamppinfo);
		fclose($installsys);
		$lamppinstaller = "newinstall";
	}

	/// Bazı * update.sys dosyalarını bulun ve install.sys dosyasını değiştir ...
	$path = $partwampp."\install\\";
	$hdl = opendir($path);
	while ($res = readdir($hdl)) { //Tüm lampp sys dosyalarında arama
		$array[] = $res;
	 }
	closedir($hdl);
	$werte = count($array);
	for ($q = 2; $q < $werte; $q++) {
		if (($array[$q] == $perlupdatesys) || ($array[$q] == $pythonupdatesys) || ($array[$q] == $serverupdatesys) || ($array[$q] == $utilsupdatesys) || ($array[$q] == $javaupdatesys) || ($array[$q] == $otherupdatesys)) {
			$updatesysroot = $partwampp."\install\\".$array[$q];
			if (file_exists($updatesysroot)) {
				$datei = fopen($updatesysroot, 'r');
				unset($updatezeile);

				$i = 0;
				while (!feof($datei)) {
					$zeile = fgets($datei, 255);
					$updatezeile[] = $zeile;
					@list($left, $right) = preg_split("/=/", $updatezeile[0]);
					$left = preg_replace("/\s/i", "", $left);
					$left = preg_replace("/\r\n/i", "", $left);
					$right = preg_replace("/\r\n/i", "", $right);
					$update = $left;
					$update = strtolower($update);
					$updateversion = trim($right);
					$updateversionzahl = preg_split('|[.-]|', $updateversion); 
					if (!isset($updateversionzahl[3])) {
						$updateversionzahl[3] = '';
					}
					$updateinc = "lampp".$update.".inc";
					$updateconf = $update.".conf";
					
					$i++;
				}
				fclose($datei);
        // echo "  $update $updateversion icin yapilandirin\r\n";
        // echo "  Version $lamppversion icin yapilandirma\r\n";
				if (file_exists($installsysroot)) {
					$datei = fopen($installsysroot, 'r');
					unset($newzeile);
					$i = 0;
					while (!feof($datei)) {
						$zeile = fgets($datei, 255);
						$newzeile[] = $zeile;
						$i++;
					}
					fclose($datei);

		/// İnstall.sys için * update.syse dosyasını analiz eder
		
		//// 05.08.2017 => Eklenti
		$datei = fopen($installsysroot,'w'); 
        if($datei) 
            { 
                for($z=0;$z<$i+1;$z++) 
                { 
					if (0 === stripos(trim($newzeile[$z]), trim($update))) 

					{
						list ($left, $right) = preg_split ("/=/", $newzeile[$z]);
						
						$left = preg_replace ("/\s/i","",$left);
						$left = preg_replace ("/\r\n/i","",$left);
						$right = trim(preg_replace ("/\r\n/i","",$right));
						$currentversionzahl = preg_replace ("/\./i","",sprintf('%0-6s',$right)); 
						if ($currentversionzahl == 0 )
						{
							$updatemake="makenew"; // New installation
							$putnew="$update = $updateversion\r\n";
							fputs($datei, $putnew);
						}
						elseif ($currentversionzahl < $updateversionzahl)
						{
							$updatemake="update";  // Update installation
							$putnew="$update = $updateversion\r\n"; 
							fputs($datei, $putnew);
						}
						else
						{
							$updatemake="doppelt"; // Installation is current
							fputs($datei,$newzeile[$z]); 
						}

					}
					else 
					{ 
					fputs($datei,$newzeile[$z]); 
					}
				}
			}
	fclose($datei);
//// 11.09.2017 => Eklenti için eski bölüm burada sona eriyor

				
					if (($updatemake == "makenew") || ($updatemake=="doppelt")) {
						include_once "$partwampp\install\\$updateinc";
					}
				}
				// Perl, Python veya Java için httpd.conf değişikliği (sadece tekli)
				////// YOL DEĞİŞTİRME YOLU APACHE 2.2
				/* if ($update == "perl") {
					$includehttpdconf = "\r\n\r\nInclude conf/extra/perl.conf";
				} */
				if ($update == "python") {
					$includehttpdconf = "\r\n\r\nInclude conf/extra/python.conf";
				}
				/* if ($update == "java") {
					$includehttpdconf = "\r\n\r\nInclude conf/extra/java.conf";
				} */
		if ((($update == "perl") || ($update == "python") || ($update == "java")) && ($updatemake == "makenew")) {
					$datei = fopen($confhttpdroot, 'a');
					if ($datei) {
						fputs($datei, $includehttpdconf);
					}
					@fclose($datei);
					/* $datei = fopen($confhttpd2root, 'a');
					if ($datei) {
						fputs($datei, $includehttpdconf);
					}
					fclose($datei);
					$datei = fopen($confhttpd3root, 'a');
					if ($datei) {
						fputs($datei, $includehttpdconf);
					}
					fclose($datei); */ //26.11.2017 eski
				}

				unlink($updatesysroot);
			}
		}
	}

	if (($lamppinstaller == "newinstall") || ($lamppinstaller == "newpath")) {
		if ($lamppinstaller == "newinstall") {
			/// İlk başlatma yalnızca ana paketler
			if (file_exists("$partwampp\install\\lamppbasic.inc")) {
				include_once "$partwampp\install\\lamppbasic.inc";
			}
			if (file_exists("$partwampp\install\\lamppserver.inc")) { 
				include_once "$partwampp\install\\lamppserver.inc";
			}
		} else {
			/// Tüm paketleri bul
			if (file_exists("$partwampp\install\\lamppbasic.inc")) {
				include_once "$partwampp\install\\lamppbasic.inc";
			}
			if (file_exists("$partwampp\install\\lamppserver.inc")) {
				include_once "$partwampp\install\\lamppserver.inc";
			}
			if (file_exists("$partwampp\install\\lamppperl.inc")) {
				include_once "$partwampp\install\\lamppperl.inc";
			}
			if (file_exists("$partwampp\install\\lampppython.inc")) {
				include_once "$partwampp\install\\lampppython.inc";
			}
			if (file_exists("$partwampp\install\\lampputils.inc")) {
				include_once "$partwampp\install\\lampputils.inc";
			}
			if (file_exists("$partwampp\install\\lamppjava.inc")) {
				include_once "$partwampp\install\\lamppjava.inc";
			}
			if (file_exists("$partwampp\install\\lamppother.inc")) {
				include_once "$partwampp\install\\lamppother.inc";
			}
			$updatemake = "nothingtodo";
		}
	}

	$scount = count($slashrootreal);
	$bcount = count($backslashrootreal);
	$dbcount = count($doublebackslashrootreal);

	/////////////////// lampp yolu değişiyor ///////////////////
	if ($lamppinstaller == "newpath") {
		set_time_limit(0);
		define('NEWSTDIN', fopen("php://stdin", "r")); 
		while ($BS == "0") {
			echo "\n  lampp kurulumunu yenilemek ister misiniz?\n";
			echo "  lampp kurulumu simdi guncellenmeli mi?\n\n";
			echo "  1) Simdi yenile! (Simdi guncelleyin!)\n";
			echo "  x) Cik (cikis)\n";

			switch (trim(fgets(NEWSTDIN, 256))) { 
				case 1:
					$BS = 1;
					echo "\r\n  lampp simdi yenileniyor ...\r\n";
					echo "  lampp simdi guncellenmektedir ...\r\n\r\n";
					sleep(1);
					break;

				case "x":
					echo "\r\n  Yenileme talep uzerine sona eriyor ... cikis\r\n";
					echo "  Guncelleme istek uzerine iptal edildi ...\r\n";
					sleep(3);
					exit;

				default:
					exit;
			}
		}
		fclose(NEWSTDIN); 
	}

	/////////////////// Eklenti modüllerini httpd için yapılandırabilirsiniz. ///////////////////
	if (file_exists($installsysroot)) {
		$datei = fopen($installsysroot, 'r');
		unset($newzeile);
		$i = 0;
		while (!feof($datei)) {
			$zeile = fgets($datei, 255);
			@list($left, $right) = preg_split ("/=/", $zeile);
			$left = preg_replace("/\s/i", "", $left);
			$left = preg_replace("/\r\n/i", "", $left);
			$right = preg_replace("/\r\n/i", "", $right);
			$right = preg_replace("/\./i", "", $right);
			if (strtolower($right) > 0) {
				if (strtolower($left) == "perl") {
					$perlactive = "yes";
				}
				if (strtolower($left) == "python") {
					$pythonactive = "yes";
				}
				if (strtolower($left) == "java") {
					$javaactive = "yes";
				}
			}
		}
		fclose($datei);
	}

	/////////////////// Yeni kurulm hedefi ///////////////////
	if (($lamppinstaller == "newinstall") || ($BS == 1) || ($updatemake == "makenew") || ($updatemake == "doppelt")) {
		if ($BS == "1") {
			echo "  Ayar dosyalarindaki tum yollari yeniliyor ... \r\n\r\n";
		}

		echo "  Awk ile lampp yapilandir ";
		$system = system("echo '%os%'");
		if ($system != "'Windows_NT'") {
			$system = "Windows";
			echo "  $system 98/ME/HOME";
		}
		echo "  Yapilandirma dosyalari guncelleniyor ... lutfen bekleyin ...";
		if ($lamppinstaller == "newinstall") {
			if ($system == "Windows") {
				$confhttpdroot = $partwampp."\apache\\conf\\httpd.conf";
				$includewin = "Win32DisableAcceptEx\r\n";
				echo "\r\n  AcceptEx Winsocks v2 destegini devre disi birakin";
				$datei = fopen($confhttpdroot, 'r');
				unset($newzeile);
				$i = 0;
				while (!feof($datei)) {
					$zeile = fgets($datei, 255);
					$newzeile[] = $zeile;
					$i++;
				}
				fclose($datei);
				$datei = fopen($confhttpdroot, 'w');
				if ($datei) {
					for ($z = 0; $z < $i + 1; $z++) {
						if (preg_match("/Win32DisableAcceptEx/i", $newzeile[$z])) {
							fputs($datei, $includewin);
						} else {
							fputs($datei, $newzeile[$z]);
						}
					}
				}
				fclose($datei);
			} else {
				$confhttpdroot = $partwampp."\apache\\conf\\httpd.conf";
				$includewin = "# Win32DisableAcceptEx\r\n";
				// echo "\r\n  AcceptEx Winsocks v2 destegini etkinlestirme";
				$datei = fopen($confhttpdroot, 'r');
				$i = 0;
				unset($newzeile);
				while (!feof($datei)) {
					$zeile = fgets($datei, 255);
					$newzeile[] = $zeile;
					$i++;
				}
				fclose($datei);
				$datei = fopen($confhttpdroot, 'w');
				if ($datei) {
					for ($z = 0; $z < $i + 1; $z++) {
						if (preg_match("/Win32DisableAcceptEx/i", $newzeile[$z])) {
							fputs($datei, $includewin);
						} else {
							fputs($datei, $newzeile[$z]);
						}
					}
				}
				fclose($datei);
			}
		}

		$substit = "\"".$substit."\"";
		$trans = array(
			"^" => "\\\\^",
			"." => "\\\\.",
			"[" => "\\\\[",
			"$" => "\\\\$",
			"(" => "\\\\(",
			")" => "\\\\)",
			"+" => "\\\\+",
			"{" => "\\\\{"
		);
		$substit = strtr($substit, $trans);
		for ($i = 0; $i <= $bcount; $i++) {
			///// 15.01.2018: Aynı dosya adlarına sahip tüm dosyalar için /////
			if ($backslash[$i] == "") {
				$upbackslashrootreal = $backslashrootreal[$i];
			} else {
				$configname = $backslash[$i];
				$upbackslashrootreal = $backslashrootreal[$configname].$configname;

			}
			$backslashawk = preg_replace("/\\\\/i", "\\\\\\\\", $upbackslashrootreal);
			$backslashawk = "\"".$backslashawk;

			$awkconfig = $backslashawk."\"";
			$awkconfigtemp = $backslashawk."temp\"";
			$configreal = $upbackslashrootreal;
			$configtemp = $upbackslashrootreal."temp";

			///////////// Bölüm SET YENİ ekler / güncelleme VEYA SİLME için yapılandırmalar /////////////
			$configrealnew = $upbackslashrootreal.".new";
			if (!file_exists($configreal) && file_exists($configrealnew)) {
				if (!@copy($configrealnew, $configreal)) {
				} else {
					unlink($configrealnew);
				}
			} elseif (file_exists($configrealnew)) {
				unlink($configrealnew);
			}

			if ($updatemake == "doppelt") {;
				break;
			}
      // echo "Ayıklama: Şimdi  $awkconfig ile çalışmak ... \r\n";
			$awkrealm = $awkexe." -v DIR=".$awknewdir." -v CONFIG=".$awkconfig. " -v CONFIGNEW=".$awkconfigtemp. "  -v SUBSTIT=".$substit." -f ".$awk;
			if (file_exists($awk) && file_exists($awkexe) && file_exists($configreal)) {
				$handle = popen($awkrealm, 'w'); 
				pclose($handle);
			}

			if (file_exists($configtemp) && file_exists($configreal)) {
				if (!@copy($configtemp, $configreal)) {
				} else {
					unlink($configtemp);
				}
			}
		}

		$doublesubstit = "\"".$doublesubstit."\"";
		$trans = array(
			"^" => "\\\\^",
			"." => "\\\\.",
			"[" => "\\\\[",
			"$" => "\\\\$",
			"(" => "\\\\(",
			")" => "\\\\)",
			"+" => "\\\\+",
			"{" => "\\\\{"
		);
		$doublesubstit = strtr($doublesubstit, $trans);
		for ($i = 0; $i <= $dbcount; $i++) {
			///// 23.01.2018: Aynı dosya adlarına sahip tüm dosyalar için /////
			if ($doublebackslash[$i] == "") {
				$updoublebackslashrootreal = $doublebackslashrootreal[$i];
			} else {
				$configname = $doublebackslash[$i];
				$updoublebackslashrootreal = $doublebackslashrootreal[$configname].$configname;

			}
			$doublebackslashawk = preg_replace("/\\\\/i", "\\\\\\\\", $updoublebackslashrootreal);
			$doublebackslashawk = "\"".$doublebackslashawk;

			$awkconfig = $doublebackslashawk."\"";
			$awkconfigtemp = $doublebackslashawk."temp\"";
			$configreal = $updoublebackslashrootreal;
			$configtemp = $updoublebackslashrootreal."temp";

			///////////// Bölüm SET YENİ ekler / güncelleme VEYA SİLME için yapılandırmalar /////////////
			$configrealnew = $updoublebackslashrootreal.".new";
			if (!file_exists($configreal) && file_exists($configrealnew)) {
				if (!@copy($configrealnew, $configreal)) {
				} else {
					unlink($configrealnew);
				}
			} elseif (file_exists($configrealnew)) {
				unlink($configrealnew);
			}

			if ($updatemake == "doppelt") {
				break;
			}
      // echo "Ayıklama: Simdi $awkconfig ile calisma ... \r\n";
			$awkrealm = $awkexe." -v DIR=".$awkdoublebackslashdir." -v CONFIG=".$awkconfig. " -v CONFIGNEW=".$awkconfigtemp. "  -v SUBSTIT=".$doublesubstit." -f ".$awk;

			if (file_exists($awk) && file_exists($awkexe) && file_exists($configreal)) {
				$handle = popen($awkrealm, 'w'); 
				pclose($handle);
			}

			if (file_exists($configtemp) && file_exists($configreal)) {
				if (!@copy($configtemp, $configreal)) {
				} else {
					unlink($configtemp);
				}
			}
		}

		$substitslash = "\"".$substitslash."\"";
		$trans = array(
			"^" => "\\\\^",
			"." => "\\\\.",
			"[" => "\\\\[",
			"$" => "\\\\$",
			"(" => "\\\\(",
			")" => "\\\\)",
			"+" => "\\\\+",
			"{" => "\\\\{"
		);
		$substitslash = strtr($substitslash, $trans);
		for ($i = 0; $i <= $scount; $i++) {
			///// 06.02.2018: Aynı dosya adlarına sahip tüm dosyalar için /////
			if ($slash[$i] == "") {
				$upslashrootreal = $slashrootreal[$i];
			} else {
				$configname = $slash[$i];
				$upslashrootreal = $slashrootreal[$configname].$configname;
			}
			$slashawk = preg_replace("/\\\\/i", "\\\\\\\\", $upslashrootreal);
			$slashawk = "\"".$slashawk;
			$awkconfig = $slashawk."\"";
			$awkconfigtemp = $slashawk."temp\"";
			$configreal = $upslashrootreal;
			$configtemp=$upslashrootreal."temp";

			///////////// Bölüm SET YENİ ekler / güncelleme VEYA SİLME için yapılandırmalar /////////////
			$configrealnew = $upslashrootreal.".new";
			if (!file_exists($configreal) && file_exists($configrealnew)) {
				if (!@copy($configrealnew, $configreal)) {
				} else {
					unlink($configrealnew);
				}
			} elseif (file_exists($configrealnew)) {
				unlink($configrealnew);
			}

			if ($updatemake == "doppelt") {
				break;
			}
      // echo "Ayıklama: Şimdi$awkconfig ile çalışmak ... \r\n";
			$awkrealm = $awkexe." -v DIR=".$awkslashdir." -v CONFIG=".$awkconfig. " -v CONFIGNEW=".$awkconfigtemp. "  -v SUBSTIT=".$substitslash." -f ".$awk;

			if (file_exists($awk) && file_exists($awkexe) && file_exists($configreal)) {
				$handle = popen($awkrealm, 'w'); 
				pclose($handle);
			}

			if (file_exists($configtemp) && file_exists($configreal)) {
				if (!@copy($configtemp, $configreal)) {
				} else {
					unlink($configtemp);
				}
			}
		}

		if (($lamppinstaller == "newpath") || ($BS == 1)) {
			if (file_exists($installsysroot)) {
				$datei = fopen($installsysroot, 'r');
				unset($newzeile);
				$i = 0;
				while (!feof($datei)) {
					$zeile = fgets($datei, 255);
					$newzeile[] = $zeile;
					$i++;
				}
				fclose($datei);
			}

			$datei = fopen($installsysroot, 'w');
			if ($datei) {
				for ($z = 0; $z < $i + 1; $z++) {
					if (preg_match("/DIR/i", $newzeile[$z])) {
						$includenewdir = "DIR = $partwampp\r\n";
						fputs($datei, $includenewdir);
					} else {
						$includenewdir = $newzeile[$z];
						fputs($datei, $includenewdir);
					}
				}
			}
			fclose($datei);
		}

		////////// Bazı yeni dosyaları değiştirin (kopyalayın) ////////////////
		$phpversion = trim(@file_get_contents($partwampp."\\install\\.phpversion")); 
		switch ($phpversion) {
			case 4:
				$phpbin = $partwampp."\\apache\\bin\\php.ini";
				$phpcgi = $partwampp."\\php\\php4\\php.ini";
				@copy($phpbin, $phpcgi);
				$phpbin = $partwampp."\\php\\php5.ini";
				$phpcgi = $partwampp."\\php\\php.ini";
				@copy($phpbin, $phpcgi);
				break;

			default:
				$phpbin = $partwampp."\\apache\\bin\\php.ini";
				$phpcgi = $partwampp."\\php\\php.ini";
				@copy($phpbin, $phpcgi);
				$phpbin = $partwampp."\\php\\php4\\php4.ini";
				$phpcgi = $partwampp."\\php\\php4\\php.ini";
				@copy($phpbin, $phpcgi);
				break;
		}

		$workersbin = $partwampp."\\tomcat\\conf\\workers.properties";
		$workersjk = $partwampp."\\tomcat\\conf\\jk\\workers.properties";
		if (file_exists($workersbin)) {
			copy($workersbin,$workersjk);
		}

		echo "  TAMAMLANDI!\r\n\r\n";
		echo "\r\n  ##### lampp yenileme ve guncelleme Krulumu basarili | Iyi Eglenceler! #####\r\n\r\n\r\n";
		sleep(1);
	}

	//////////////// Modül seçimi  ////////////////
	if ((($perlactive == "yes") || ($pythonactive == "yes") || ($javaactive == "yes")) && ($update == "")) {
		$u = 1;

		if ($perlactive == "yes") {
			$moduleconf = "conf/extra/perl.conf";
			$moduleconfigure = "MOD_PERL";
			$u++;
		}
		if ($pythonactive == "yes") {
			$moduleconf = "conf/extra/pyton.conf";
			$moduleconfigure = "MOD_PYTHON";
			$u++;
		}
		if ($javaactive == "yes") {
			$moduleconf = "conf/extra/java.conf";
			$moduleconfigure = "MOD_JDK";
			$u++;
		}

		set_time_limit(0);
		define('NEWSTDIN', fopen("php://stdin", "r"));
		while ($CS == "0") {
			echo "\n  Lutfen seciminizi yapiniz!\n";
			echo "  Lutfen simdi sec!\n\n";
			if ($perlactive == "yes") {
				echo "  1) MOD_PERL ile yapilandirma (mit MOD_PERL)\n";
				echo "  2) MOD_PERL olmadan yapilandirma (ohne MOD MOD_PERL)\n";
			}
			if ($pythonactive == "yes") {
				echo "  3) MOD_PYTHON ile yapilandirma (MOD_PYTHON ile)\n";
				echo "  4) MOD_PYTHON olmadan yapilandirma (MOD_PYTHON olmadan)\n";
			}
			if ($javaactive == "yes") {
				echo "  5) MOD_JK ile yapilandirma (mit MOD_JK)\n";
				echo "  6) MOD_JK'siz yapilandirma (ohne MOD_JK)\n";
			}
			echo "  x) Cik (cikis)\n";

			switch (trim(fgets(NEWSTDIN, 256))) {
				case 1:
					$CS = 1;
					echo "\r\n  MOD_PERL ile configure lampp baslatiliyor ...\r\n";
					sleep(1);
					break;

				case 2:
					$CS = 2;
					echo "\r\n  MOD_PERL olmadan configure lampp baslatiliyor ...\r\n";
					sleep(1);
					break;

				case 3:
					$CS = 3;
					echo "\r\n  MOD_PYTHON ile yapilandirmak lampp baslatiliyor ...\r\n";
					sleep(1);
					break;

				case 4:
					$CS = 4;
					echo "\r\n  MOD_PYTHON olmadan yapilandirmak lampp baslatiliyor ...\r\n";
					sleep(1);
					break;

				case 5:
					$CS = 5;
					echo "\r\n  MOD_JDK ile yapilandirmak xampp baslatiliyor ...\r\n";
					sleep(1);
					break;

				case 6:
				$CS = 6;
				echo "\r\n  MOD_JDK olmadan yapilandirmak lampp baslatiliyor ...\r\n";
				sleep(1);
				break;

				case "x":
					echo "\r\n  Talep uzerine kurulum sonlandiriliyor ... Cikis\r\n";
					echo "  Kurulum istek uzerine iptal edildi ...\r\n";
					sleep(3);
					exit;

				default:
					exit;
			}
		}
		fclose(NEWSTDIN);

		if ($CS == 1) {
			$include = "Include conf/extra/perl.conf"; $searchstring="conf/extra/perl.conf";
		}
		if ($CS == 2) {
			$include = "# Include conf/extra/perl.conf"; $searchstring="conf/extra/perl.conf";
		}
		if ($CS == 3) {
			$include = "Include conf/extra/python.conf"; $searchstring="conf/extra/python.conf";
		}
		if ($CS == 4) {
			$include = "# Include conf/extra/python.conf"; $searchstring="conf/extra/python.conf";
		}
		if ($CS == 5) {
			$include = "Include conf/extra/java.conf"; $searchstring="conf/extra/java.conf";
		}
		if ($CS == 6) {
			$include = "# Include conf/extra/java.conf"; $searchstring="conf/extra/java.conf";
		}

		if ($CS > 0) {
			$i = 0;
			$datei = fopen($confhttpdroot, 'r');
			while (!feof($datei)) {
				$zeile = fgets($datei, 255);
				$newzeile[] = $zeile;
				$i++;
			}
			fclose($datei);
			$datei = fopen($confhttpdroot, 'w');
			if ($datei) {
				for ($z = 0; $z < $i + 1; $z++) {
					if (preg_match('/'.$searchstring.'/i', $newzeile[$z])) {
						fputs($datei, $include);
					} else {
						fputs($datei, $newzeile[$z]);
					}
				}
			}
			fclose($datei);
			unset($newzeile);
			
			/// 26.02.2018 çünkü 1.25'den eski
			/* $i = 0;
			$datei = fopen($confhttpd2root, 'r');
			while (!feof($datei)) {
				$zeile = fgets($datei, 255);
				$newzeile[] = $zeile;
				$i++;
			}
			fclose($datei);
			$datei = fopen($confhttpd2root, 'w');
			if ($datei) {
				for($z = 0; $z < $i + 1; $z++) {
					if (eregi($searchstring, $newzeile[$z])) {
						fputs($datei, $include);
					} else {
						fputs($datei, $newzeile[$z]);
					}
				}
			}
			fclose($datei);
			unset($newzeile);
			$i = 0;
			$datei = fopen($confhttpd3root, 'r');
			while (!feof($datei)) {
				$zeile = fgets($datei, 255);
				$newzeile[] = $zeile;
				$i++;
			}
			fclose($datei);
			$datei = fopen($confhttpd3root, 'w');
			if ($datei) {
				for ($z = 0; $z < $i + 1; $z++) {
					if (eregi($searchstring, $newzeile[$z])) {
						fputs($datei, $include);
					} else {
						fputs($datei, $newzeile[$z]);
					}
				}
			}
			fclose($datei);
			unset($newzeile);*/ 
			echo "  Tamam!\r\n\r\n";
		}
	}

	if (file_exists($partwampp.'\install\serverupdate.inc')) { 
		include $partwampp.'\install\serverupdate.inc';
		unlink($partwampp.'\install\serverupdate.inc');
		echo "\r\n".'Hazır.'."\r\n";
	}

	if ($updatemake == "") {
		$updatemake="nothingtodo";
	}

	if (($updatemake == "nothingtodo") && ($lamppinstaller == "nothingtodo") && (($CS < 1) || ($CS == ""))) {
		echo "\r\n\r\n Hersey yolunda, yapacak bir sey bulamadim ;) \r\n\r\n\r\n";
	}

	exit;
?>

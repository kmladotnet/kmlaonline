<?php
if($is_mobile)
	$themename="mobile";
else
	$themename="dev";
function head_theme(){
	global $themename;
	echo	'<link rel="stylesheet" class="page-specific-css" href="/theme/'.$themename.'/theme.css?v=2.0" />';
	//$i = rand(0,179);
	//$flip=array('QLVXRYZ', 'AAKRTWO', 'XHHVVFY', 'MFIPDTE', 'KXPDGYR', 'JPWPZEZ', 'PWIVNAU', 'XERDIQN', 'OBNJVYY', 'THPPKRT', 'OQLEJZF', 'LMUFQWT', 'YNLGHFC', 'DAQUMVY', 'ZQRSHHV', 'TKWLXNX', 'YRJKUGD', 'OLJCGRF', 'KWBITAZ', 'MRDQVCE', 'RBQVDLI', 'BEIYYCU', 'PLJQMMS', 'SMHLULX', 'XRYYQHT', 'TSSNXLI', 'HVKNXNL', 'HWQUFJK', 'OKLTYKO', 'YNSFGCR', 'GSXYBCW', 'SJSJCHJ', 'MFTVQRS', 'GYFXKEA', 'FJECUBB', 'UMBOPDI', 'BWJBJDH', 'KONSJPO', 'VNZFXNH', 'KXVSZVY', 'LUYKVQN', 'PMHBZOQ', 'BRIDHGM', 'PGPQKIH', 'FMNWUCP', 'TPYVBXP', 'CKVOXFL', 'URTMHLP', 'BXSLOOJ', 'RZHJBSF', 'AHUDITF', 'GGSWQOL', 'QZKKXRB', 'UPJDNTY', 'UOXIBMR', 'MIAIZCV', 'FTMMFWL', 'YBZWWGA', 'LVKYYCD', 'FFJTPIT', 'FPIPBPF', 'FZMAPAA', 'ZQVKZUN', 'OMQSOLR', 'AGHITVX', 'YZJVGRS', 'EIEQCFR', 'NLUTISE', 'XZKJGWE', 'GOJTUXG', 'OICRCLD', 'FWQOBQH', 'ZGHZAVE', 'KJFWNDI', 'LRNNIKO', 'ALCPJFJ', 'EPCLXLZ', 'HYZVADH', 'TIJIGIW', 'LUULUXP', 'LKBJJOK', 'CTLWDXJ', 'VASHNGH', 'BIJHWDG', 'MEAHIPX', 'IABHMOF', 'DPPENEJ', 'RLTLSUV', 'LIYRUVP', 'SRUPFHL', 'RLJRHAR', 'LDYFWCG', 'SZNYEBG', 'BXQRCIZ', 'KTJESIH', 'ZQVCGZQ', 'MUIDMCV', 'MPUMMKK', 'WBLYUGR', 'SEPDGMI', 'OTCAOVL', 'DZQSEZI', 'OWKAKHI', 'FWYHDNO', 'SMRRRJI', 'UUCJHOP', 'YEZUCXB', 'HRWPMHE', 'OLPAAGC', 'UIZBCYE', 'RMXYLVE', 'NNUZPUW', 'HOBEKFA', 'SSQLFTJ', 'SGLSNGD', 'AJMLRSV', 'VARXBZI', 'IZUAWLZ', 'OKXZTGZ', 'VMQAUSV', 'DZVUWHS', 'PCODZNH', 'VJIWVLR', 'TRSOIQS', 'DKRNYDZ', 'FPSIWPM', 'VITBDLZ', 'QYWNHRY', 'LKQPWQQ', 'LMVLZOD', 'JJOINRX', 'VCMPAOT', 'OGOVIWE', 'IGSNPST', 'JBLQDSU', 'CWOHOWP', 'COMCATV', 'FLICGUJ', 'PNRUWGA', 'VRRCHHY', 'QPBSGFV', 'UTYCZMX', 'VAWPXRW', 'YWNOIQE', 'BAXDGID', 'UICTONE', 'LWKQXED', 'ICDXBBR', 'UBXXXID', 'OSUBRFT', 'LOGCIGS', 'QFWJAYA', 'WNUSIHA', 'GITACGX', 'QJBCQOZ', 'ZGNPUMU', 'WBTJALP', 'RMDHDJA', 'ZWKDWLP', 'GBRKOFD', 'RLPQFLO', 'BUKJUND', 'AOFOSTV', 'XLPBRWV', 'IEMSPDW', 'YAFZZFS', 'BABAZIR', 'JIZQWRV', 'FKDIDRE', 'YQIOMOT', 'NJGGNTX', 'CVXSERM', 'EEZIGAP', 'NJAAMWR', 'FHUDESY', 'BXOJLRX', 'HBLNYXX', 'GTNIPHS', 'KIMWHAL', 'BKXHBMM', 'LUFFMXT', 'XWWIBKF', 'PUZKAVA', 'FOKTKMD', 'SBBTSXN', 'UFRHJPD', 'GRGOQYF', 'SWQKMZB', 'EQTQBHM', 'LSDRFFG', 'CHGYULW', 'TJTVQZI', 'OXXPEMX', 'STVJBTT', 'HHJKOWJ', 'TKULRVH', 'JECEYJY', 'NECBDMC', 'TMBWGCG', 'YCLFDSW', 'PWGPAWG', 'NEMIKSS', 'IVRUTFF', 'LUKGKQV', 'RLTMNHW', 'ZZWNKOW', 'ZIROWSD', 'GJVIRVB', 'DZVQAXB', 'XIWEFGR', 'DKILFSH', 'GVZVLIA', 'KSDDKNE', 'ZRCNOWH', 'GORVMFO', 'FAHWXWK', 'SNATDTN', 'LBNSUWO', 'HERCYHH', 'ZMORXJE', 'BSXUGYM', 'PKTOQSG', 'UHUFPEQ', 'ZSFQPMN', 'XXBZXXK', 'CSRLXNM', 'KPKWZZP', 'RGBVSKK', 'AOPHBXG', 'FMMMOTT', 'PKVXWNK', 'YJBFBNE', 'HFOXDNL', 'YUQBVVH', 'BFOVUWG', 'EJEXZPA', 'IZCNBJT', 'XQPNKGT', 'KJZDKIA', 'JPAOFYV', 'UWJECRY', 'MWVCMIQ', 'JYJWNCB', 'RZDKXKQ', 'WSWHKZZ', 'BHTAVBE', 'SJOJNUM', 'FDTTTRS', 'QMQKXOB', 'HFDQGTQ', 'HVVGLJI', 'QYGDQRL', 'ABJGNOX', 'PABOKGL', 'DJGDFLW', 'ZSSVCTJ', 'QRBTFWR', 'ARRNUZT', 'JPJAPRI', 'FBZZSQY', 'PTQFWVV', 'KOLCCEQ', 'TSUUFRR', 'YYJHJLQ', 'PHJHHNK', 'HFACVHH', 'RJDMNVO', 'MJXVVYK', 'LEHKQHP', 'VIMGKXR', 'OJTGANA', 'ELREYAK', 'ABPENCW', 'XIFDYPR', 'MWYJEHS', 'IBIPYXA', 'WAZPRBR', 'NLXTQAF', 'RLIYDXM', 'RQCGXXI', 'QBFCVYR', 'LOVFFQM', 'DYXJHJB', 'WZTDSML', 'XUZNYHL', 'DZOZMNP', 'MYUOZIL', 'MXYNHRP', 'NSVXIGE', 'LDUXLEI', 'OLEBMME', 'MCQFGOF', 'SYAGCEN', 'MAEWBCB', 'DQYJMGF', 'CWQELAW', 'JWIMZEN', 'VCBDMNM', 'KDIQBQZ', 'GADXTJV', 'DFRQTTZ', 'UFGBGAY', 'BKZTYTL', 'VBLWDPE', 'LRHQQRY', 'VXNVXFO', 'IJASHQC', 'VNWIBQR', 'PJERHIC', 'OAGCNGU', 'IWKQBWQ', 'RRCFVAI', 'TCOHYMI', 'MTDHXWB', 'HZMFUQK', 'EDHLPFT', 'WRPLCAZ', 'TSBPEAP', 'JBLZGGU', 'UHTFUDD', 'EDFUBWD', 'BYXWVIO', 'MEAMKUN', 'HNFDJMW', 'XXPPUTM', 'QUCJLXS', 'FXPKOKA', 'LPMDCRO', 'KYPAETT', 'DXXFIOY', 'PXUBFUU', 'RJXTUNB', 'UFLTWPK', 'UXHFAGY', 'HBQFPOY', 'HBPLBKT', 'YHRUPDS', 'JHETPWQ', 'NRQLSSJ', 'DUCPKYY', 'HCXNPAN', 'RCVIKWN', 'CKIIVFI', 'FGLAZER', 'SNJTSMZ', 'QUDOEXK', 'DFCISCT', 'MJXFPMK', 'OXTBXRP', 'WXCEAKS', 'FVAEKSO', 'LUOYOSY', 'AMUFLXB', 'UEOXXNE', 'IUXJBDS', 'DSXEUXT', 'XAFLCDC', 'JZYYJED', 'ERGQOJF', 'PHEMQWB');
	//echo 	'<div id="'.$flip[$i].'">';
}

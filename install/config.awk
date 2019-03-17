BEGIN {
	# ORS="\\r\\n";
	# SUBSTIT = "\\\\lamppcoffee";
	# DIR = "D:\\lampp";
	# server_cmd = "D:\\lampp\\install\\server.xml";
	while (getline < CONFIG) {
		sub(SUBSTIT,DIR,$0);
		print $0 > CONFIGNEW
	}

	# print "@rem  Installation Program, second part" > "D:\\lampp\\install\\inst.bat"
	# D:\lampp\install\awk -v DIR = "C:\\lampp" -f D:\lampp\install\test.awk
}

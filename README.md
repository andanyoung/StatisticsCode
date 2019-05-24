# StatisticsCode

在写`软件著作权`时需要一份源程序的文档，于是有了这个轮子。

## run

* `git clone https://github.com/AndyYoungCN/StatisticsCode.git`
* `cd xxx/StatisticsCode`
* `php StatisticsCode.php -s --source=source_dic --output=output_dic`

---
* StatisticsCode 参数解析：
> `-l`: 限制统计行数 0或者空没限制.default 0。  
> `-i`: 忽略无效行 0不忽略,1:忽略注释；2：忽略换行、空白行；3：忽略注释、换行、空白行.default 3。  
> `-s`: //是否要输出源代码到指定文件  
> `--suffix`: 需要统计的文件统计后缀.default `.php`  
> `--source`: 源文件（相对、绝对）目录。defalt `.` （当前目录）  
> `--output`: 输出文件目录。 `./StatisticsCode.txt`

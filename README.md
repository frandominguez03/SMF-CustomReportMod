[Custom report Mod](http://custom.simplemachines.org/mods/index.php?mod=3011)

This mod will add custom option about reporting to moderators. By default in SMF 2.0 branch whenever someone reports a post to moderator a report is created in moderation center, but after installing this mod and making required changes in admin panel reports will be posted in topics in the specified board.


***Admin interface*** - ?action=admin;area=customreport

**General Settings**
- Enable disable the mod with a single click - If the admin hasn't enable this option the default report section of moderation center will be used.
- Board id to create post report.
- Quote the content of reported post.
- Count Posts of Report Board.
- Count the post when moderator solves a report.
- Use bigger input field for writing reports 
- Send email to moderators 


**Permission Settings** - ?action=admin;area=customreport;sa=permissionsettings
- Provide permissions to the group who you want to mark reports as solved over here


In posts
- [Report Solved]/[Report Unsolved]
This button appears in 1st post of report topics in report board. Only people having permission 'Can mark reports as solved/unsolved', can see this button. 

When a moderator clicks [Report Solved] button a solved topic will be created and the report post will be locked automatically.
When a moderator clicks [Report Unsolved] button the report topic will be unlocked.


[GitHub Link](https://github.com/siddhartha-gupta/SMF-CustomReportMod)
[Change log](https://github.com/siddhartha-gupta/SMF-CustomReportMod/blob/master/changelog.md)


All future ideas for this mod are most welcome, please post them in mod support thread.

*License*
 * This SMF Modification is subject to the Mozilla Public License Version
 * 1.1 (the "License"); you may not use this SMF modification except in compliance with
 * the License. You may obtain a copy of the License at
 * http://www.mozilla.org/MPL/
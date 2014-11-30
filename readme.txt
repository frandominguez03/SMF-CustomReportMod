[url=http://custom.simplemachines.org/mods/index.php?mod=3011][b]Custom report Mod[/b][/url]

This mod will add custom option about reporting to moderators. By default in SMF 2.0 branch whenever someone reports a post to moderator a report is created in moderation center, but after installing this mod and making required changes in admin panel reports will be posted in topics in the specified board.


[b][i]Admin interface[/i][/b]

[i]General Settings[/i] - ?action=admin;area=customreport
- Enable disable the mod with a single click - If the admin hasn't enable this option the default report section of moderation center will be used.
- Board id to create post report.
- Quote the content of reported post.
- Count Posts of Report Board.
- Count the post when moderator solves a report.
- Use bigger input field for writing reports 
- Send email to moderators 


[i]Permission Settings[/i] - ?action=admin;area=customreport;sa=permissionsettings
- Provide permissions to the group who you want to mark reports as solved over here


In posts
- [Report Solved]/[Report Unsolved]
This button appears in 1st post of report topics in report board. Only people having permission 'Can mark reports as solved/unsolved', can see this button. 

When a moderator clicks [Report Solved] button a solved topic will be created and the report post will be locked automatically.
When a moderator clicks [Report Unsolved] button the report topic will be unlocked.


[url=https://github.com/siddhartha-gupta/SMF-CustomReportMod]GitHub Link[/url]
[url=https://github.com/siddhartha-gupta/SMF-CustomReportMod/blob/master/changelog.md]Change log[/url]


All future ideas for this mod are most welcome, please post them in mod support thread.

[b]License[/b]
 * This SMF Modification is subject to the Mozilla Public License Version
 * 1.1 (the "License"); you may not use this SMF modification except in compliance with
 * the License. You may obtain a copy of the License at
 * http://www.mozilla.org/MPL/
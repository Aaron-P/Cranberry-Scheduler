/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Copyright (c) 2012 Aaron Papp                                               *
 *                    De'Liyuon Hamb                                           *
 *                    Shawn LeMaster                                           *
 *               All rights reserved.                                          *
 *                                                                             *
 * Developed by: Web Dynamics                                                  *
 *               Southern Illinois University Edwardsville                     *
 *               https://github.com/Aaron-P/Cranberry-Scheduler                *
 *                                                                             *
 * Permission is hereby granted, free of charge, to any person obtaining a     *
 * copy of this software and associated documentation files (the "Software"),  *
 * to deal with the Software without restriction, including without limitation *
 * the rights to use, copy, modify, merge, publish, distribute, sublicense,    *
 * and/or sell copies of the Software, and to permit persons to whom the       *
 * Software is furnished to do so, subject to the following conditions:        *
 *   1. Redistributions of source code must retain the above copyright notice, *
 *      this list of conditions and the following disclaimers.                 *
 *   2. Redistributions in binary form must reproduce the above copyright      *
 *      notice, this list of conditions and the following disclaimers in the   *
 *      documentation and/or other materials provided with the distribution.   *
 *   3. Neither the names of Web Dynamics, Southern Illinois University        *
 *      Edwardsville, nor the names of its contributors may be used to endorse *
 *      or promote products derived from this Software without specific prior  *
 *      written permission.                                                    *
 *                                                                             *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR  *
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,    *
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.  IN NO EVENT SHALL    *
 * THE CONTRIBUTORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR   *
 * OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE,       *
 * ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER *
 * DEALINGS WITH THE SOFTWARE.                                                 *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

{extends file="template.tpl"}
{block name="page_title"}Meeting Overview{/block}

{block name="page_head"}
<link rel='stylesheet' type='text/css' href='css/view_meetings.css' />
<link rel='stylesheet' type='text/css' href='css/meeting_overview.css' />
{/block}

{block name="page_content"}
<div id="stylized" class="myform">
	<h1 style="text-align:center;">Meeting Overview</h1><br />

	<label class="label">Date:</label>
	<span>{$event.Date}</span><br />

	<label class="label">Time:</label>
	<span>{$event.Start} - {$event.End}</span><br />

	<label class="label">Meeting Type:</label>
	<span>{$event.MeetingType}</span><br />
	<br />

{if $volunteers}
	<h3 style="text-align: center;">Volunteer Information</h3>

	{foreach $volunteers as $v}
		<label class="label">Name:</label>
		<span>{$v.FirstName} {$v.LastName}</span><br />

		<label class="label">e-ID:</label>
		<span>{$v.Eid}</span><br />

		<label class="label">Email:</label>
		<span><a href="mailto:{$v.Eid}@siue.edu">{$v.Eid}@siue.edu</a></span><br />
		<br />
	{/foreach}
{/if}

	<div class="spacer"></div>
</div>
<br />

<div id="stylized2" class="myform">
	<a href="#">Edit Meeting</a>
</div>
{/block}

/* select to file*/
/* query goes here */
INTO OUTFILE '/tmp/orders.csv'
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'

/* duplicate problems */
select 
    id,
    url,
    count(*) as cnt
from problems
group by url
having cnt > 1
order by cnt, id desc
;

exit

/* orphaned problems */
select 
    pp.id,
    pp.name,
    pp.url,
    t2p.topic_id,
    p2a.*
from problems as pp
left join 12m_topic_prob as t2p
    on pp.id=t2p.problem_id
left join 12m_prob_ans as p2a
    on pp.id=p2a.prob_id 
where 
t2p.topic_id is null
;

/* problem count by class & topic */
select 
    class.name,
    topic.name,
    count(*)
from problems
inner join 12m_topic_prob t2p
    on problems.id=t2p.problem_id
inner join topic
    on topic.id=t2p.topic_id
inner join 12m_class_topic c2t
    on t2p.topic_id=c2t.topic_id
inner join class
    on class.id=c2t.class_id
where 
    class.name='Chemistry 130'
group by topic.id
;

/* find duplicates in class by topic */
select 
    class.name,
    topic.name,
    topic.id,
    count(*),
    count(distinct problems.url)
from problems
inner join 12m_topic_prob t2p
    on problems.id=t2p.problem_id
inner join topic
    on topic.id=t2p.topic_id
inner join 12m_class_topic c2t
    on t2p.topic_id=c2t.topic_id
inner join class
    on class.id=c2t.class_id
inner join 
(
select 
    id,
    url,
    count(*) as cnt
from problems
group by url
having cnt = 1 /* > 1*/
order by cnt, id desc
) as res1
    on res1.url=problems.url
where 
    class.name='Chemistry 130'
group by topic.id
;

/* Verify the problmes in chapter 1-6 are unique in Chemistry */
select 
    class.name,
    topic.name,
    topic.id,
    problems.url,
    count(*)
from problems
inner join 12m_topic_prob t2p
    on problems.id=t2p.problem_id
inner join topic
    on topic.id=t2p.topic_id
inner join 12m_class_topic c2t
    on t2p.topic_id=c2t.topic_id
inner join class
    on class.id=c2t.class_id
where 
    class.name='Chemistry 130'
    and topic.id >= 50
    and topic.id <= 55
group by problems.url
order by topic.id, count(*)
;

/* responses count by class & topic */
select 
    class.name,
    topic.name,
    problems.url,
    problems.correct,
    sum(case
        when problems.correct=responses.answer then 1
        else 0
    end) as correct,
    count(*) as tried,
    sum(case
        when problems.correct=responses.answer then 1
        else 0
    end) / count(*) as rate
from responses
inner join problems on 
    problems.id=responses.prob_id
inner join 12m_topic_prob t2p on 
    responses.prob_id=t2p.problem_id
inner join topic on 
    topic.id=t2p.topic_id
inner join 12m_class_topic c2t on 
    t2p.topic_id=c2t.topic_id
inner join class on 
    class.id=c2t.class_id
where 
    class.name='Chemistry 130'
    /*
    class.name='Chemistry 130'
    class.name like 'Statistics%'
    */
group by topic.id, problems.id
having
    rate < 0.20
    and tried > 20
order by topic.id, rate
;

/* best students */
select 
    user.username,
    sum(case
        when problems.correct=responses.answer then 1
        else 0
    end) as correct,
    count(*) as tried,
    sum(case
        when problems.correct=responses.answer then 1
        else 0
    end) / count(*) as rate
from responses
inner join `user`
    on user.id=responses.user_id 
inner join problems
    on problems.id=responses.prob_id
inner join 12m_topic_prob t2p
    on responses.prob_id=t2p.problem_id
inner join topic
    on topic.id=t2p.topic_id
inner join 12m_class_topic c2t
    on t2p.topic_id=c2t.topic_id
inner join class
    on class.id=c2t.class_id
where 
    class.name='Chemistry 130'
group by responses.user_id
having
    rate > 0.5
    and tried > 20
order by tried, rate
;

/* extract of pr data for analysis */
select
'username',
'course',
'topic',
'url',
'correct',
'choice',
'rate',
'start_time',
'end_time'
union 
select 
    user.username,
    class.name as course,
    topic.name as topic,
    problems.url,
    problems.correct,
    responses.answer as choice,
    problems.tot_correct / problems.tot_tries as rate,
    responses.start_time,
    responses.end_time
from responses
inner join problems
    on problems.id=responses.prob_id
inner join 12m_topic_prob t2p
    on responses.prob_id=t2p.problem_id
inner join topic
    on topic.id=t2p.topic_id
inner join 12m_class_topic c2t
    on t2p.topic_id=c2t.topic_id
inner join class
    on class.id=c2t.class_id
inner join user
    on user.id=responses.user_id
where 
    /*class.name like 'Physics%'*/
    /*class.name like 'Statistics%'*/
    class.name='Chemistry 130'
INTO OUTFILE '/Users/jtritz/bitbucket/problemroulette/sql/pr_chem.csv'
/*INTO OUTFILE '/Users/jtritz/bitbucket/problemroulette/sql/pr_physics.csv'*/
/*INTO OUTFILE '/Users/jtritz/bitbucket/problemroulette/sql/pr_stats.csv'*/
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
;

/* chemistry usage by day */
select
    DATE(start_time) date,
    count(*)
from responses 
where 
    class.name='Chemistry 130'
group by DAYOFYEAR(start_time)
order by DAYOFYEAR(start_time)
;

/* response counts by day of year */
select 
    DAYOFYEAR(start_time) as doy,
    count(*)
from responses
inner join problems
    on problems.id=responses.prob_id
inner join 12m_topic_prob t2p
    on responses.prob_id=t2p.problem_id
inner join topic
    on topic.id=t2p.topic_id
inner join 12m_class_topic c2t
    on t2p.topic_id=c2t.topic_id
inner join class
    on class.id=c2t.class_id
where 
    class.name like 'Physics%'
group by DAYOFYEAR(start_time)
order by DAYOFYEAR(start_time)
;

/* count the users and responses for a topic */
select 
    count(user_id),
    count(distinct user_id)
from responses
inner join 12m_topic_prob t2p
    on responses.prob_id=t2p.problem_id
inner join topic
    on topic.id=t2p.topic_id
inner join 12m_class_topic c2t
    on t2p.topic_id=c2t.topic_id
where 
    c2t.class_id = 10
;

/* usage by student */
select 
    user.username,
    class.name,
    /*
    Physics
    case
        when dayofyear(responses.start_time) >      dayofyear('2013-09-03') 
            and dayofyear(responses.start_time) <=  dayofyear('2013-10-03') then 1
        when dayofyear(responses.start_time) >      dayofyear('2013-10-03') 
            and dayofyear(responses.start_time) <=  dayofyear('2013-10-31') then 2
        when dayofyear(responses.start_time) >      dayofyear('2013-10-31') 
            and dayofyear(responses.start_time) <=  dayofyear('2013-11-21') then 3
        when dayofyear(responses.start_time) >      dayofyear('2013-11-21') 
            and dayofyear(responses.start_time) <=  dayofyear('2013-12-19') then 4
        else -1
    end as exam,
    Chemistry
    case
        when dayofyear(responses.start_time) >      dayofyear('2013-09-03') 
            and dayofyear(responses.start_time) <=  dayofyear('2013-10-16') then 1
        when dayofyear(responses.start_time) >      dayofyear('2013-10-16') 
            and dayofyear(responses.start_time) <=  dayofyear('2013-11-20') then 2
        when dayofyear(responses.start_time) >      dayofyear('2013-11-20') 
            and dayofyear(responses.start_time) <=  dayofyear('2013-12-19') then 3
        else -1
    end as exam,    
    MCDB
    case
        when dayofyear(responses.start_time) >      dayofyear('2013-09-03') 
            and dayofyear(responses.start_time) <=  dayofyear('2013-09-30') then 1
        when dayofyear(responses.start_time) >      dayofyear('2013-09-30') 
            and dayofyear(responses.start_time) <=  dayofyear('2013-10-28') then 2
        when dayofyear(responses.start_time) >      dayofyear('2013-10-28') 
            and dayofyear(responses.start_time) <=  dayofyear('2013-11-18') then 3
        when dayofyear(responses.start_time) >      dayofyear('2013-11-18') 
            and dayofyear(responses.start_time) <=  dayofyear('2013-12-19') then 4
        else -1
    end as exam,
    Stats
    case
        when dayofyear(responses.start_time) >      dayofyear('2013-09-03') 
            and dayofyear(responses.start_time) <=  dayofyear('2013-10-17') then 1
        when dayofyear(responses.start_time) >      dayofyear('2013-10-17') 
            and dayofyear(responses.start_time) <=  dayofyear('2013-11-14') then 2
        when dayofyear(responses.start_time) >      dayofyear('2013-11-14') 
            and dayofyear(responses.start_time) <=  dayofyear('2013-12-19') then 3
        else -1
    end as exam,
    */
    sum(case
        when problems.correct=responses.answer then 1
        else 0
    end) as correct,
    count(*) as tried,
    count(distinct dayofyear(responses.start_time)) as days,
    sum(case
        when problems.correct=responses.answer then 1
        else 0
    end) / count(*) as rate,
    round(sum(to_seconds(responses.end_time) - to_seconds(responses.start_time))/count(*), 0) as avg_time,
    round(sum(to_seconds(responses.end_time) - to_seconds(responses.start_time)), 0) as tot_time
from responses
inner join `user`
    on user.id=responses.user_id 
inner join problems
    on problems.id=responses.prob_id
inner join 12m_topic_prob t2p
    on responses.prob_id=t2p.problem_id
inner join topic
    on topic.id=t2p.topic_id
inner join 12m_class_topic c2t
    on t2p.topic_id=c2t.topic_id
inner join class
    on class.id=c2t.class_id
where 
    /*
    1
    class.name like 'Physics%'
    class.name ='Chemistry 130'
    class.name = 'Statistics 250'
    */
    class.name ='MCDB 310'
group by concat(responses.user_id, class.name, exam)
order by user.username, exam, class.name, days, tried
;

/* all response data */
select 
    user.username as who,
    class.name as class,
    topic.name as topic,
    problems.id as prob_id,
    problems.name as prob_name,
    problems.url as prob_url,
    problems.correct as correct_answer,
    responses.answer as resp_answer,
    unix_timestamp(responses.start_time) as resp_start_time,
    unix_timestamp(responses.end_time) as resp_end_time
from responses
inner join `user`
    on user.id=responses.user_id 
inner join problems
    on problems.id=responses.prob_id
inner join 12m_topic_prob t2p
    on responses.prob_id=t2p.problem_id
inner join topic
    on topic.id=t2p.topic_id
inner join 12m_class_topic c2t
    on t2p.topic_id=c2t.topic_id
inner join class
    on class.id=c2t.class_id
where 
    /*
    class.name ='MCDB 310'
    class.name ='Chemistry 130'
    class.name = 'Statistics 250'
    */
    class.name like 'Physics%'
order by user.username, class.name, resp_start_time
;

/* schema */
desc 12m_class_topic;
desc 12m_prob_ans;
desc 12m_prob_ans_stats;    
desc 12m_topic_prob;    
desc `class`;          
desc problems;              
desc responses;             
desc session_table;
desc stats;
desc topic;
desc `user`;

/* new usage by student */
select 
    user.username,
    class.name,
    /*
    Physics
    case
        when dayofyear(responses.start_time) >      dayofyear('2014-01-08') 
            and dayofyear(responses.start_time) <=  dayofyear('2014-02-06') then 1
        when dayofyear(responses.start_time) >      dayofyear('2014-02-06') 
            and dayofyear(responses.start_time) <=  dayofyear('2014-02-28') then 2
        when dayofyear(responses.start_time) >      dayofyear('2014-03-01') 
            and dayofyear(responses.start_time) <=  dayofyear('2014-03-01') then 3
        else -1
    end as exam,
    Chemistry
    case
        when dayofyear(responses.start_time) >      dayofyear('2014-01-03') 
            and dayofyear(responses.start_time) <=  dayofyear('2014-02-01') then 1
        when dayofyear(responses.start_time) >      dayofyear('2014-02-01') 
            and dayofyear(responses.start_time) <=  dayofyear('2014-02-28') then 2
        when dayofyear(responses.start_time) >      dayofyear('2014-03-01') 
            and dayofyear(responses.start_time) <=  dayofyear('2014-03-01') then 3
        else -1
    end as exam,    
    MCDB
    case
        when dayofyear(responses.start_time) >      dayofyear('2013-09-03') 
            and dayofyear(responses.start_time) <=  dayofyear('2013-09-30') then 1
        when dayofyear(responses.start_time) >      dayofyear('2013-09-30') 
            and dayofyear(responses.start_time) <=  dayofyear('2013-10-28') then 2
        when dayofyear(responses.start_time) >      dayofyear('2013-10-28') 
            and dayofyear(responses.start_time) <=  dayofyear('2013-11-18') then 3
        when dayofyear(responses.start_time) >      dayofyear('2013-11-18') 
            and dayofyear(responses.start_time) <=  dayofyear('2013-12-19') then 4
        else -1
    end as exam,
    Stats
    case
        when dayofyear(responses.start_time) >      dayofyear('2013-09-03') 
            and dayofyear(responses.start_time) <=  dayofyear('2013-10-17') then 1
        when dayofyear(responses.start_time) >      dayofyear('2013-10-17') 
            and dayofyear(responses.start_time) <=  dayofyear('2013-11-14') then 2
        when dayofyear(responses.start_time) >      dayofyear('2013-11-14') 
            and dayofyear(responses.start_time) <=  dayofyear('2013-12-19') then 3
        else -1
    end as exam,
    */
    sum(case
        when problems.correct=responses.answer then 1
        else 0
    end) as correct,
    count(distinct responses.id) as tried,
    count(distinct dayofyear(responses.start_time)) as days,
    sum(case
        when problems.correct=responses.answer then 1
        else 0
    end) / count(*) as rate,
    round(sum(to_seconds(responses.end_time) - to_seconds(responses.start_time))/count(*), 0) as avg_time,
    round(sum(to_seconds(responses.end_time) - to_seconds(responses.start_time)) / 60 / 60, 2) as tot_hours
from responses
inner join `user`
    on user.id=responses.user_id 
inner join problems
    on problems.id=responses.prob_id
inner join 12m_topic_prob t2p
    on responses.prob_id=t2p.problem_id
inner join topic
    on topic.id=t2p.topic_id
inner join 12m_class_topic c2t
    on t2p.topic_id=c2t.topic_id
inner join class
    on class.id=c2t.class_id
where 
    /*
    1
    class.name = 'Statistics 250'
    class.name ='MCDB 310'
    and user.username='asjaqua'
    class.name ='Chemistry 130'
    */
    class.name like 'Physics%'
    and year(responses.start_time) = 2014
group by concat(responses.user_id, class.name, exam)
having exam=1 or exam=2 or exam=3
order by user.username, exam, class.name, days, tried
;

/* sanity check on temporal distribution */
select
    username,
    from_unixtime(round(UNIX_TIMESTAMP(start_time) / 3600, 0)*3600) as tt,
    count(*) as cnt
from responses 
inner join user on 
    user.id=responses.user_id
where 
    user_id='963'
    or user_id='656'
    /*
    select * from responses where user_id='racheby';
    select * from responses where user_id='yoohap';
    */
group by tt
order by username, tt
; 

/* jimmy's PR update query */
select 
    res1.who,
    res1.days_logged_in,
    res1.tot_probs,
    round(res1.tot_probs / res1.days_logged_in, 0) as avg_per_day,
    res2.days_over_3
from
(
/* by user */
select 
    user.username as who,
    class.name,
    count(distinct dayofyear(responses.start_time)) as days_logged_in,
    count(distinct responses.id) as tot_probs
from responses
inner join `user`
    on user.id=responses.user_id 
inner join problems
    on problems.id=responses.prob_id
inner join 12m_topic_prob t2p
    on responses.prob_id=t2p.problem_id
inner join topic
    on topic.id=t2p.topic_id
inner join 12m_class_topic c2t
    on t2p.topic_id=c2t.topic_id
inner join class
    on class.id=c2t.class_id
where 
    class.name ='Chemistry 130'
    and dayofyear(responses.start_time) >   dayofyear('2014-04-02') 
    and dayofyear(responses.start_time) <=  dayofyear('2014-04-04')
    /*
    and dayofyear(responses.start_time) >   dayofyear('2014-04-02') 
    and dayofyear(responses.start_time) <=  dayofyear('2014-04-07')
    and dayofyear(responses.start_time) >   dayofyear('2014-04-02') 
    and dayofyear(responses.start_time) <=  dayofyear('2014-04-09')
    ------------
    and dayofyear(responses.start_time) >   dayofyear('2014-02-02') 
    and dayofyear(responses.start_time) <=  dayofyear('2014-02-14')
    and dayofyear(responses.start_time) <=  dayofyear('2014-02-05')
    and dayofyear(responses.start_time) <=  dayofyear('2014-02-07')
    and dayofyear(responses.start_time) <=  dayofyear('2014-02-10')
    and dayofyear(responses.start_time) <=  dayofyear('2014-02-12')
    and dayofyear(responses.start_time) <=  dayofyear('2014-02-17')
    and dayofyear(responses.start_time) <=  dayofyear('2014-02-19')
    */
group by concat(class.name, responses.user_id)
order by class.name, user.username
) res1

left join
(
/* days over 3 problems */
select 
    resB.who,
    count(*) as days_over_3
from
(
select 
    user.username as who,
    class.name,
    count(responses.id) as prob_per_day
from responses
inner join `user`
    on user.id=responses.user_id 
inner join problems
    on problems.id=responses.prob_id
inner join 12m_topic_prob t2p
    on responses.prob_id=t2p.problem_id
inner join topic
    on topic.id=t2p.topic_id
inner join 12m_class_topic c2t
    on t2p.topic_id=c2t.topic_id
inner join class
    on class.id=c2t.class_id
where 
    class.name ='Chemistry 130'
    and dayofyear(responses.start_time) >   dayofyear('2014-04-02') 
    and dayofyear(responses.start_time) <=  dayofyear('2014-04-04')
    /*
    and dayofyear(responses.start_time) >   dayofyear('2014-04-02') 
    and dayofyear(responses.start_time) <=  dayofyear('2014-04-07')
    and dayofyear(responses.start_time) >   dayofyear('2014-04-02') 
    and dayofyear(responses.start_time) <=  dayofyear('2014-04-09')
    ------------
    and dayofyear(responses.start_time) >   dayofyear('2014-02-02') 
    and dayofyear(responses.start_time) <=  dayofyear('2014-02-14')
    and dayofyear(responses.start_time) <=  dayofyear('2014-02-05')
    and dayofyear(responses.start_time) <=  dayofyear('2014-02-07')
    and dayofyear(responses.start_time) <=  dayofyear('2014-02-10')
    and dayofyear(responses.start_time) <=  dayofyear('2014-02-12')
    and dayofyear(responses.start_time) <=  dayofyear('2014-02-17')
    and dayofyear(responses.start_time) <=  dayofyear('2014-02-19')
    */
group by concat(class.name, responses.user_id, dayofyear(responses.start_time))
having prob_per_day > 2
order by class.name, user.username
) resB
group by who
) res2 on
    res1.who = res2.who
order by res1.days_logged_in desc, res1.tot_probs desc
;

/* single serve usage by student */
select 
    user.username,
    class.name,
    case
        when dayofyear(responses.start_time) >      dayofyear('2014-01-04') 
            and dayofyear(responses.start_time) <=  dayofyear('2014-04-06') then 1
        else -1
    end as semester,    
    sum(case
        when problems.correct=responses.answer then 1
        else 0
    end) as correct,
    count(distinct responses.id) as tried,
    count(distinct dayofyear(responses.start_time)) as days,
    sum(case
        when problems.correct=responses.answer then 1
        else 0
    end) / count(*) as rate,
    round(sum(to_seconds(responses.end_time) - to_seconds(responses.start_time))/count(*), 0) as avg_time,
    round(sum(to_seconds(responses.end_time) - to_seconds(responses.start_time)) / 60 / 60, 2) as tot_hours
from responses
inner join `user`
    on user.id=responses.user_id 
inner join problems
    on problems.id=responses.prob_id
inner join 12m_topic_prob t2p
    on responses.prob_id=t2p.problem_id
inner join topic
    on topic.id=t2p.topic_id
inner join 12m_class_topic c2t
    on t2p.topic_id=c2t.topic_id
inner join class
    on class.id=c2t.class_id
where 
    class.name ='Chemistry 130'
    and year(responses.start_time) = 2014
group by concat(responses.user_id, class.name, semester)
having semester=1 
order by user.username, semester, class.name, days, tried
;

/* new usage by student Winter 2014 */
select 
    user.username,
    class.name as course,
    /*
    Physics
    case
        when dayofyear(responses.start_time) >      dayofyear('2014-01-08') 
            and dayofyear(responses.start_time) <=  dayofyear('2014-02-06') then 1
        when dayofyear(responses.start_time) >      dayofyear('2014-02-06') 
            and dayofyear(responses.start_time) <=  dayofyear('2014-02-28') then 2
        when dayofyear(responses.start_time) >      dayofyear('2014-03-01') 
            and dayofyear(responses.start_time) <=  dayofyear('2014-03-01') then 3
        else -1
    end as exam,
    Chemistry
    case
        when dayofyear(responses.start_time) >      dayofyear('2014-01-03') 
            and dayofyear(responses.start_time) <=  dayofyear('2014-02-01') then 1
        when dayofyear(responses.start_time) >      dayofyear('2014-02-01') 
            and dayofyear(responses.start_time) <=  dayofyear('2014-02-28') then 2
        when dayofyear(responses.start_time) >      dayofyear('2014-03-01') 
            and dayofyear(responses.start_time) <=  dayofyear('2014-03-01') then 3
        else -1
    end as exam,    
    MCDB
    case
        when dayofyear(responses.start_time) >      dayofyear('2013-09-03') 
            and dayofyear(responses.start_time) <=  dayofyear('2013-09-30') then 1
        when dayofyear(responses.start_time) >      dayofyear('2013-09-30') 
            and dayofyear(responses.start_time) <=  dayofyear('2013-10-28') then 2
        when dayofyear(responses.start_time) >      dayofyear('2013-10-28') 
            and dayofyear(responses.start_time) <=  dayofyear('2013-11-18') then 3
        when dayofyear(responses.start_time) >      dayofyear('2013-11-18') 
            and dayofyear(responses.start_time) <=  dayofyear('2013-12-19') then 4
        else -1
    end as exam,
    Stats
    case
        when dayofyear(responses.start_time) >      dayofyear('2014-01-08') 
            and dayofyear(responses.start_time) <=  dayofyear('2014-02-20') then 1
        when dayofyear(responses.start_time) >      dayofyear('2014-02-20') 
            and dayofyear(responses.start_time) <=  dayofyear('2014-04-03') then 2
        when dayofyear(responses.start_time) >      dayofyear('2014-04-03') 
            and dayofyear(responses.start_time) <=  dayofyear('2014-04-24') then 3
        else -1
    end as exam,
    */
    sum(case
        when problems.correct=responses.answer then 1
        else 0
    end) as correct,
    count(distinct responses.id) as tried,
    count(distinct dayofyear(responses.start_time)) as distinct_days,
    sum(case
        when problems.correct=responses.answer then 1
        else 0
    end) / count(*) as rate,
    round(sum(to_seconds(responses.end_time) - to_seconds(responses.start_time))/count(*), 0) as avg_time,
    round(sum(to_seconds(responses.end_time) - to_seconds(responses.start_time)) / 60 / 60, 2) as tot_hours
from responses
inner join `user`
    on user.id=responses.user_id 
inner join problems
    on problems.id=responses.prob_id
inner join 12m_topic_prob t2p
    on responses.prob_id=t2p.problem_id
inner join topic
    on topic.id=t2p.topic_id
inner join 12m_class_topic c2t
    on t2p.topic_id=c2t.topic_id
inner join class
    on class.id=c2t.class_id
where 
    /*
    1
    class.name ='MCDB 310'
    and user.username='asjaqua'
    class.name ='Chemistry 130'
    class.name like 'Physics%'
    */
    class.name = 'Statistics 250'
    and year(responses.start_time) = 2014
    and responses.answer != 0  /* avoid skips */
group by concat(responses.user_id, class.name, exam)
having exam=1 or exam=2 or exam=3
order by user.username, exam, class.name, days, tried
;

/* extract of pr data for analysis */
select 
    user.username as username,
    class.name as course,
    topic.name as topic,
    problems.url url,
    problems.correct as correct_answer,
    responses.answer as student_answer,
    /*problems.tot_correct / problems.tot_tries as rate, */
    UNIX_TIMESTAMP(responses.start_time) as start_time,
    UNIX_TIMESTAMP(responses.end_time) as end_time
from responses
inner join problems
    on problems.id=responses.prob_id
inner join 12m_topic_prob t2p
    on responses.prob_id=t2p.problem_id
inner join topic
    on topic.id=t2p.topic_id
inner join 12m_class_topic c2t
    on t2p.topic_id=c2t.topic_id
inner join class
    on class.id=c2t.class_id
inner join user
    on user.id=responses.user_id
where 
    1 
    and dayofyear(responses.start_time) > dayofyear('2014-01-08') 
    and dayofyear(responses.start_time) <= dayofyear('2014-04-24')
    /*
    and class.name like 'Physics%'
    and class.name like 'Statistics%'
    */
    and class.name='Chemistry 130'
;



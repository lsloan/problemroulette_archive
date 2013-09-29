
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
    count(*) as tried,
    sum(case
        when problems.correct=responses.answer then 1
        else 0
    end) as correct,
    sum(case
        when problems.correct=responses.answer then 1
        else 0
    end) / count(*) as rate
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
    class.name='Chemistry 130'
group by topic.id, problems.id
order by topic.id, rate
;



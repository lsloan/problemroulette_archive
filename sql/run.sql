use prexpansion;

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



exit


